<?php


namespace App\Http\Controllers;

use App\Http\Requests\CreateComplaintResponseRequest;
use App\Http\Requests\InsertAuctionRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Game;
use App\Http\Requests\InsertProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Auction;
use App\Models\CartItem;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductComment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{

    function showDashboard() {
        $user = Auth::user();
        $users = User::where('role', 'user')->get();
        $shop = $user->shop;

        $totalProducts = $shop->products() ? $shop->products()->count() : 0;
        $activeProducts = $shop->products() ? $shop->products()->where('stok', '>', 0)->count() : 0;
        $totalOrders = $shop->orderItems() ? $shop->orderItems()->count() : 0;

        $runningTransactions = $shop->running_transactions; // Saldo yang masih dalam proses (belum bisa dicairkan)
        $shopBalance = $shop->shop_balance; // Saldo yang sudah bisa dicairkan
        $categories = Category::orderBy('category_name')->get();

        return view('pages.seller.dashboard', compact('shop','totalProducts', 'activeProducts', 'totalOrders', 'runningTransactions', 'shopBalance', 'users', 'categories'));
    }

    public function showComplaints()
    {
        $shop = Auth::user()->shop;

        $complaints = Complaint::where('seller_id', Auth::id())
            ->with(['orderItem.product', 'buyer', 'response'])
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('category_name')->get();
        return view('pages.seller.complaints', compact('complaints', 'categories', 'shop'));
    }

    public function showComplaintDetail($complaintId)
    {
        $complaint = Complaint::where('seller_id', Auth::id())
            ->where('complaint_id', $complaintId)
            ->with(['orderItem.product', 'buyer', 'response'])
            ->firstOrFail();

        $shop = Auth::user()->shop;
        $categories = Category::orderBy('category_name')->get();
        return view('pages.seller.complaint_detail', compact('complaint', 'categories', 'shop'));
    }

     public function respondComplaint(CreateComplaintResponseRequest $request, $complaintId)
    {
        $complaint = Complaint::where('seller_id', Auth::id())
            ->where('complaint_id', $complaintId)
            ->where('status', 'waiting_seller')
            ->firstOrFail();

        if ($complaint->response()->exists()) {
            return redirect()->route('seller.complaints.show', $complaintId)->with('error', 'Anda sudah memberikan tanggapan');
        }

        $validated = $request->validated();
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store("complaint_responses/{$complaint->complaint_id}", 'public');
        }
        ComplaintResponse::create([
            'complaint_id' => $complaint->complaint_id,
            'message' => $validated['message'],
            'attachment' => $attachmentPath
        ]);
        $complaint->update(['status' => 'waiting_admin']);
        return redirect()->route('seller.complaints.index')->with('success', 'Tanggapan berhasil dikirim. Menunggu keputusan admin.');

    }

    function showSellerAuctions()
    {
        $auctions = Auction::where('seller_id', Auth::user()->user_id)
            ->with(['product' => function ($q) {
                $q->withTrashed()
                ->with([
                    'shop'     => fn ($q) => $q->withTrashed(),
                    'category' => fn ($q) => $q->withTrashed(),
                    'game'     => fn ($q) => $q->withTrashed(),
                ]);
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        $categories = Category::orderBy('category_name')->get();
        $shop = Auth::user()->shop;

        return view('pages.seller.auctions', compact('auctions', 'categories', 'shop'));
    }

    public function toggleShopStatus()
    {
        $shop = Auth::user()->shop;

        if (!$shop) {
            return redirect()->route('seller.dashboard')->with('error', 'Toko tidak ditemukan');
        }

        $newStatus = $shop->status === 'open' ? 'closed' : 'open';
        $shop->update(['status' => $newStatus]);

        $message = $newStatus === 'open'? 'Toko berhasil dibuka!': 'Toko berhasil ditutup!';

        return redirect()->route('seller.dashboard')->with('success', $message);
    }

    function showReviews(Request $request)
    {
        $shop = Auth::user()->shop;

        $query = ProductComment::whereHas('product', function($q) use ($shop) {
            $q->where('shop_id', $shop->shop_id);
        })->with(['product', 'user', 'orderItem']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $comments = $query->latest('created_at')->paginate(20);

        $products = $shop->products()->orderBy('product_name')->get();

        $totalReviews = ProductComment::whereHas('product', function($q) use ($shop) {
            $q->where('shop_id', $shop->shop_id);
        })->count();

        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = ProductComment::whereHas('product', function($q) use ($shop) {
                $q->where('shop_id', $shop->shop_id);
            })->where('rating', $i)->count();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('partials.reviews_table', compact('comments'))->render(),
                'pagination' => $comments->links()->render()
            ]);
        }

        return view('pages.seller.reviews', compact('comments', 'products', 'totalReviews', 'ratingDistribution'));
    }

    function showIncomingOrders()
    {
        $categories = Category::orderBy('category_name')->get();
        $user = Auth::user();
        $orders = OrderItem::query()
            ->leftJoin('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.product_id', '=', 'order_items.product_id')
            ->leftJoin('shops', 'shops.shop_id', '=', 'products.shop_id')
            ->where(function ($q) {
                $q->where('shops.owner_id', Auth::id())   // PEMILIK TOKO
                ->orWhereNull('products.product_id');  // product hard delete
            })
            ->whereIn('order_items.status', ['paid', 'completed', 'cancelled','shipped'])
            ->where('orders.status', '=', 'paid')
            ->orderBy('order_items.paid_at', 'desc')       // SORT BY ORDER
            ->select('order_items.*')
            ->with([
                'order.account',
                'product' => function ($q) {
                    $q->withTrashed()
                    ->with([
                        'shop'     => fn ($q) => $q->withTrashed(),
                        'category' => fn ($q) => $q->withTrashed(),
                        'game'     => fn ($q) => $q->withTrashed(),
                    ]);
                }
                ])
            ->orderBy('orders.created_at', 'desc')       // SORT BY ORDER
            ->paginate(20);

        return view('pages.seller.orders', compact('categories', 'orders'));
    }

    public function shipOrder($orderItemId)
    {
        $orderItem = OrderItem::where('order_item_id', $orderItemId)
            ->where('shop_id', Auth::user()->shop->shop_id)
            ->where('status', 'paid')
            ->firstOrFail();

        $orderItem->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        $shop = $orderItem->shop;
        $shop->increment('running_transactions', $orderItem->subtotal);

        return redirect()->route('seller.incoming_orders.index')->with('success', 'Pesanan berhasil dikirim!');

    }

    public function cancelOrder($orderItemId)
    {
        $orderItem = OrderItem::where('order_item_id', $orderItemId)
            ->where('shop_id', Auth::user()->shop->shop_id)
            ->where('status', 'paid')
            ->firstOrFail();

        $orderItem->update([
            'status' => 'cancelled'
        ]);
        $buyer = $orderItem->order->account;
        $buyer->increment('balance', $orderItem->subtotal);
        $orderItem->product->increment('stok', $orderItem->quantity);
        return redirect()->route('seller.incoming_orders.index')->with('success', 'Pesanan dibatalkan dan saldo buyer telah dikembalikan!');

    }

    public function showCreateAuctionForm()
    {
        $categories = Category::orderBy('category_name')->get();
        $games = Game::all();
        $product = null;
        return view('pages.seller.create_auction', compact('categories', 'games', 'product'));
    }

    public function showSellerAuctionDetail($auctionId)
    {
        $auction = Auction::where('seller_id', Auth::user()->user_id)
            ->with([
                'product' => function ($q) {
                    $q->withTrashed()
                        ->with([
                            'shop'     => fn ($q) => $q->withTrashed(),
                            'category' => fn ($q) => $q->withTrashed(),
                            'game'     => fn ($q) => $q->withTrashed(),
                        ]);
                },
                'bids.user',
                'highestBid.user',
                'winner.user'
            ])
            ->findOrFail($auctionId);

        $topBids = $auction->bids()
            ->with('user')
            ->orderByDesc('bid_price')
            ->limit(10)
            ->get();

        $isOpen = $auction->status === 'running' 
            && now()->between($auction->start_time, $auction->end_time);

        $categories = Category::orderBy('category_name')->get();
        $shop = Auth::user()->shop;

        return view('pages.seller.auction_detail', compact(
            'auction', 
            'topBids', 
            'isOpen', 
            'categories', 
            'shop'
        ));
    }

    public function index(Request $request)
    {
        $query = Product::withTrashed()
            ->where('shop_id', Auth::user()->shop->shop_id)
            ->with(['game' => function($q) {
                $q->withTrashed();
            }, 'category' => function($q) {
                $q->withTrashed();
            }]);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->where('type', 'normal')->latest()->get();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.seller.product', compact('products', 'categories'));
    }

    public function create()
    {
        $games = Game::all();
        $product = null;
        $categories = Category::orderBy('category_name')->get();

        return view('pages.seller.create', compact( 'games', 'product', 'categories'));
    }

    public function store(InsertProductRequest $request)
    {
        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('product_img')) {
            $sellerId = Auth::user()->shop->shop_id;
            $imagePath = $request->file('product_img')->store("seller/{$sellerId}/products", 'public');
        }

        Product::create([
            'product_name' => $validated['product_name'],
            'description' => $validated['description'],
            'product_img' => $imagePath,
            'stok' => $validated['stok'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'game_id' => $validated['game_id'],
            'shop_id' => Auth::user()->shop->shop_id,
            'rating' => 0,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::where('shop_id', Auth::user()->shop->shop_id)->findOrFail($id);
        $games = Game::orderBy('game_name')->get();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.seller.create', compact('product', 'games', 'categories'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::withTrashed()->where('shop_id', Auth::user()->shop->shop_id)->findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('product_img')) {
            if ($product->product_img) {
                Storage::disk('public')->delete($product->product_img);
            }

            $sellerId = Auth::user()->shop->shop_id;
            $validated['product_img'] = $request->file('product_img')->store("seller/{$sellerId}/products", 'public');
        }

        $product->update($validated);

        if ($product->trashed()) {
            $category = Category::find($validated['category_id']);
            $game = Game::find($validated['game_id']);

            if ($category && $game) {
                $product->restore();
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function restore($id)
    {

        $product = Product::onlyTrashed()
                ->with(['category', 'game'])
                ->where('shop_id', Auth::user()->shop->shop_id)
                ->findOrFail($id);

        if (!$product->category || !$product->game) {
            return redirect()
                ->route('seller.products.index')
                ->with(
                    'error',
                    'Tidak bisa mengembalikan produk karena kategori atau game tidak tersedia.'
                );
        }

        if ($product->category->deleted_at || $product->game->deleted_at) {
            return redirect()
                ->route('seller.products.index')
                ->with(
                    'error',
                    'Tidak bisa mengembalikan produk karena kategori atau game sudah dihapus.'
                );
        }

        $product->restore();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dikembalikan!');
    }

    public function destroy($id)
    {

        $count_in_order_item = OrderItem::join('orders', 'orders.order_id', '=', 'order_items.order_id')
                                ->where('order_items.product_id', $id)
                                ->whereNotIn('order_items.status', ['cancelled', 'completed'])
                                ->where('orders.status', 'paid')
                                ->count();

        if ($count_in_order_item > 0) {
            return redirect()->route('seller.products.index')->with('error', 'Produk tidak dapat dihapus karena ada di pesanan pengguna.');
        }

        $product = Product::where('shop_id', Auth::user()->shop->shop_id)->findOrFail($id);

        // if ($product->product_img) {
        //     Storage::disk('public')->delete($product->product_img);
        // }

        CartItem::where('product_id', $product->product_id)->delete();
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function createAuction(InsertAuctionRequest $req)
    {
        $req->validated();

        $imagePath = null;
        if ($req->hasFile('product_img')) {
            $sellerId = Auth::user()->shop->shop_id;
            $imagePath = $req->file('product_img')->store("seller/{$sellerId}/products", 'public');
        }

        DB::beginTransaction();

        try {
            $product = Product::create([
                'shop_id' => Auth::user()->shop->shop_id,
                'product_name' => $req->product_name,
                'description' => $req->description,
                'product_img' => $imagePath,
                'stok' => $req->stok,
                'price' => $req->price,
                'category_id' => $req->category_id,
                'game_id' => $req->game_id,
                'rating' => 0,
                'type' => 'auction',
            ]);

            $auction = Auction::create([
                'product_id' => $product->product_id,
                'seller_id' => Auth::user()->user_id,
                'start_price' => $req->price,
                'current_price' => $req->price,
                'start_time' => $req->start_time,
                'end_time' => $req->end_time,
                'status' => 'pending',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat lelang: ' . $e->getMessage())->withInput();
        }

        DB::commit();

        return redirect()->route('seller.auctions.index')->with('success', 'Lelang berhasil dibuat!');
    }

    public function getCategoriesByGame($gameId)
    {
        $categories = Game::findOrFail($gameId)->gamesCategories()->whereHas('category', function($query) {    $query->whereNull('deleted_at'); })->with('category')->get()->pluck('category')->filter();

        return response()->json($categories);
    }

}
