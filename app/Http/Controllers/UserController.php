<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CreateComplaintRequest;
use App\Http\Requests\InputProductCommentRequest;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AuctionWinner;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Game;
use App\Models\OrderItem;
use App\Models\ProductComment;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // VIEW
    public function showCreateComplaint($orderItemId)
    {
        $orderItem = OrderItem::whereHas('order', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->where('order_item_id', $orderItemId)
        ->where('status', 'shipped')
        ->firstOrFail();

        if ($orderItem->complaint()->exists()) {
            return redirect()->route('user.orders.detail', $orderItem->order_id)->with('error', 'Anda sudah mengajukan komplain untuk produk ini');
        }

        $categories = Category::orderBy('category_name')->get();
        return view('pages.user.create_complaint', compact('orderItem', 'categories'));
    }

    public function storeComplaint(CreateComplaintRequest $request, $orderItemId)
    {
        $orderItem = OrderItem::whereHas('order', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->where('order_item_id', $orderItemId)
        ->where('status', 'shipped')
        ->firstOrFail();

        if ($orderItem->complaint()->exists()) {
            return redirect()->route('user.orders.detail', $orderItem->order_id)->with('error', 'Anda sudah mengajukan komplain');
        }

        $validated = $request->validated();

        if($request->hasFile('proof_img')){

            $proofPath = $request->file('proof_img')->store("complaints/{$orderItem->order_item_id}", 'public');
        }

        Complaint::create([
            'order_item_id' => $orderItem->order_item_id,
            'buyer_id' => Auth::id(),
            'seller_id' => $orderItem->shop->owner_id,
            'description' => $validated['description'],
            'proof_img' => $proofPath,
            'status' => 'waiting_seller'
        ]);
        return redirect()->route('user.complaints.index')->with('success', 'Komplain berhasil diajukan. Menunggu tanggapan seller.');

    }

    public function showComplaints()
    {
        $complaints = Complaint::where('buyer_id', Auth::id())
            ->with(['orderItem.product', 'orderItem.shop', 'response'])
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('category_name')->get();
        return view('pages.user.complaints', compact('complaints', 'categories'));
    }

    public function showComplaintDetail($complaintId)
    {
        $complaint = Complaint::where('buyer_id', Auth::id())
            ->where('complaint_id', $complaintId)
            ->with(['orderItem.product', 'orderItem.shop', 'response'])
            ->firstOrFail();

        $categories = Category::orderBy('category_name')->get();
        return view('pages.user.complaint_detail', compact('complaint', 'categories'));
    }


    public function showCart()
    {
        $user = Auth::user();
        $user_cart = $user->cart()->first();

        if ($user_cart) {
            $user_cart->cartItems()
                ->whereHas('product', function ($q) {
                    $q->whereNotNull('deleted_at');
                })
                ->delete();
        }

        $cartItems = $user_cart
                    ? $user_cart->cartItems()
                        ->whereHas('product', function ($q) {
                            $q->whereNull('deleted_at');
                        })
                        ->with([
                            'product' => function ($q) {
                                $q->withTrashed()
                                ->with(['category' => fn ($c) => $c->withTrashed(),
                                        'game'     => fn ($g) => $g->withTrashed()]);
                            }
                        ])
                        ->get()
                    : collect();

        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.cart', compact('cartItems', 'categories'));
    }

    public function showOrders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('shop')->orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.my_order', compact('orders', 'categories'));
    }

    public function showOrderDetail($orderId)
    {
        $user = Auth::user();
        $order = $user->orders()
                ->with([
                    'orderItems.product' => function ($query) {
                        $query->withTrashed();
                    },
                    'shop',
                    'orderItems.comment'
                ])
                ->where('order_id', $orderId)
                ->firstOrFail();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.order_detail', compact('order', 'categories'));
    }

    public function confirmOrder($orderItemId)
    {
        $orderItem = OrderItem::whereHas('order', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->where('order_item_id', $orderItemId)
        ->where('status', 'shipped')
        ->firstOrFail();

        $orderItem->update([
            'status' => 'completed'
        ]);

        $shop = $orderItem->shop;
        $shop->decrement('running_transactions', $orderItem->subtotal);
        $shop->increment('shop_balance', $orderItem->subtotal);

        return redirect()->route('user.orders.detail', $orderItem->order_id)->with('success', 'Pesanan berhasil dikonfirmasi!');

    }

    public function storeReview(InputProductCommentRequest $request, $orderItemId)
    {
        try {
            $validated = $request->validated();

            $user = Auth::user();

            $orderItem = OrderItem::with(['order', 'product'])
                ->where('order_item_id', $orderItemId)
                ->whereHas('order', function($q) use ($user) {
                    $q->where('user_id', $user->user_id);
                })
                ->firstOrFail();

            if ($orderItem->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa review produk yang sudah berhasil dibeli.'
                ], 400);
            }

            if ($orderItem->hasReview()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk ini sudah direview.'
                ], 400);
            }

            $comment = ProductComment::create([
                'product_id' => $orderItem->product_id,
                'user_id' => $user->user_id,
                'order_item_id' => $orderItem->order_item_id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil ditambahkan',
                'data' => [
                    'rating' => $comment->rating,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->format('d M Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showCartPartial()
    {
        $user = Auth::user();
        $user_cart = $user->cart()->first();
        $cartItems = $user_cart
            ? $user_cart->cartItems()->with('product')->get()
            : collect();
        $categories = Category::orderBy('category_name')->get();

        return response()->json([
            'html' => view('pages.user.cart-partial', compact('cartItems', 'categories'))->render()
        ]);
    }

    public function showHome() {
        $owners = User::where('role', 'seller')->get();

        $featuredGames = Game::withCount(['products' => function($query) {
                $query->whereHas('shop', function($q) {
                    $q->where('status', 'open')->whereHas('owner');
                })
                ->where('type', 'normal')
                ->where('stok', '>', 0)
                ->whereNull('deleted_at');
            }])
            // ->having('products_count', '>', 0)
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();

        $latestProductsQuery = Product::with(['game', 'shop', 'category'])
            ->where('type', 'normal')
            ->whereHas('shop', function($query) {
                $query->where('status', 'open')->whereHas('owner');
            })
            ->where('stok', '>', 0)
            ->whereNull('deleted_at');

        $latestProducts = $latestProductsQuery->latest()->take(12)->get();

        $topShops = Shop::where('shops.status', 'open')
            ->whereHas('owner')
            // ->whereHas('products', function($q) {
            //     $q
            //     ->where('stok', '>', 0)
            //     ->where('type', 'normal')
            //     ->whereNull('deleted_at');
            // })
            ->withCount(['orderItems as total_buyers' => function($query) {
                $query->where('order_items.status', 'completed')
                    ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                    ->distinct('orders.user_id');
            }])
            ->orderBy('total_buyers', 'desc')
            ->orderBy('shop_rating', 'desc')
            ->take(6)
            ->get();

        $auctionsQuery = Auction::with(['product.shop.owner', 'product.game', 'product.category', 'highestBid.user'])
            ->whereHas('product', function($q) {
                $q->where('stok', '>', 0)
                ->whereNull('deleted_at');
            })
            ->whereHas('product.shop', function($q) {
                $q->where('status', 'open')->whereHas('owner');
            })
            ->whereHas('product.category', function($q) {
                $q->whereNull('deleted_at');
            })
            ->whereHas('product.game', function($q) {
                $q->whereNull('deleted_at');
            })
            ->where('end_time', '>', now())
            ->whereIn('auctions.status', ['running'])
            ->orderBy('created_at', 'desc');

        $auctions = $auctionsQuery->get();

        $categories = Category::withCount(['products' => function($query) {
                $query->where('stok', '>', 0)
                    ->where('type', 'normal')
                    ->whereHas('shop', function($q) {
                        $q->where('status', 'open')->whereHas('owner');
                    })
                    ->whereNull('deleted_at');
            }])
            ->whereNull('deleted_at')
            ->orderBy('category_name')
            ->get();

        return view('pages.user.home', compact('featuredGames', 'latestProducts', 'topShops', 'owners', 'categories', 'auctions'));
    }

    public function showGames(Request $request)
    {
        $query = Game::withCount(['products'=> function($q){
            $q->where('type', 'normal')
            ->whereHas('game',function($query){
                $query->whereNull('deleted_at');
            })
            ->where('stok','>',0)
            ->whereHas('shop',function($query){
                $query->where('status','open');
            });
        }]);


        if ($request->filled('search')) {
            $query->where('game_name', 'LIKE', '%' . $request->search . '%');
        }

        $games = $query->orderBy('game_name', 'asc')->paginate(12);
        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.games', compact('games', 'categories'));
    }

    public function showGameDetail($id)
    {
        $game = Game::findOrFail($id);

        if (!$game) {
            return redirect()->route('user.home')->with('error', 'Game tidak ditemukan.');
        }

        $categories = $game->gamesCategories()
            ->whereHas('category', function($query) {
                $query->whereNull('deleted_at');
            })
            ->with('category')
            ->get()
            ->pluck('category')
            ->filter();

        $products = Product::query()
                    ->with(['shop', 'game', 'category'])
                    ->where('type', 'normal')
                    ->where('stok', '>', 0)
                    ->whereHas('shop', function ($q) {
                        $q->where('status', 'open');
                    })
                    ->latest()
                    ->paginate(12);

        return view('pages.user.game_detail', compact('game', 'categories', 'products'));
    }

    public function showProducts(Request $request)
    {
       $query = Product::withTrashed(['game', 'shop', 'category'])
        ->where('type', 'normal')
        ->whereNull('deleted_at')
        ->whereHas('shop', function($q) {
            $q->where('status', 'open')->whereHas('owner');
        })
        ->where('stok', '>', 0);

        if ($request->filled('search')) {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        $games = Game::orderBy('game_name')->get();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.products', compact('products', 'games', 'categories'));
    }

    public function showProductDetail($id)
    {
        $product = Product::with(['game', 'shop', 'category', 'comments.user'])->whereHas('game')->findOrFail($id);

        if (!$product) return redirect()->route('user.home')->with('error', 'Produk tidak ditemukan.');

        $relatedProductsQuery = Product::where('game_id', $product->game_id)
            ->with(['game', 'shop', 'category'])
            ->where('type', 'normal')
            ->whereHas('shop', function($query) {
                $query->where('status', 'open')->whereHas('owner');
            })
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->where('stok', '>', 0);

        $relatedProducts = $relatedProductsQuery->take(12)->get();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.product_detail', compact('product', 'relatedProducts', 'categories'));
    }

    public function showShop(Request $request, $id)
    {
        $shop = Shop::with(['owner', 'products' => function($query) {
            $query->whereNull('deleted_at');
        }])->findOrFail($id);

        $productsQuery = $shop->products()
            ->whereNull('deleted_at');

        if ($request->filled('search')) {
            $productsQuery->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('game_id')) {
            $productsQuery->where('game_id', $request->game_id);
        }

        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $productsQuery->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $productsQuery->where('price', '<=', $request->max_price);
        }

        switch ($request->sort) {
            case 'price_low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'rating':
                $productsQuery->orderBy('rating', 'desc');
                break;
            default:
                $productsQuery->orderBy('created_at', 'desc');
        }

        $products = $productsQuery->paginate(12);

        $totalProductsSold = $shop->orderItems()
            ->where('order_items.status', 'completed')
            ->sum('quantity');

        $totalBuyers = $shop->orderItems()
            ->where('order_items.status', 'completed')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->distinct('orders.user_id')
            ->count('orders.user_id');

        $ratingStats = [];
        $totalReviews = 0;

        for ($i = 5; $i >= 1; $i--) {
            $count = DB::table('products_comments')
                ->join('products', 'products_comments.product_id', '=', 'products.product_id')
                ->where('products.shop_id', $shop->shop_id)
                ->where('products_comments.rating', $i)
                ->whereNull('products_comments.deleted_at')
                ->count();

            $ratingStats[$i] = $count;
            $totalReviews += $count;
        }

        $ratingPercentages = [];
        foreach ($ratingStats as $rating => $count) {
            $ratingPercentages[$rating] = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
        }

        $games = Game::whereHas('products', function($q) use ($shop) {
            $q->where('shop_id', $shop->shop_id)
            ->whereNull('deleted_at');
        })->orderBy('game_name')->get();

        $categories = Category::whereHas('products', function($q) use ($shop) {
            $q->where('shop_id', $shop->shop_id)
            ->whereNull('deleted_at');
        })->orderBy('category_name')->get();

        return view('pages.user.shop_detail', compact(
            'shop',
            'products',
            'totalProductsSold',
            'totalBuyers',
            'ratingStats',
            'ratingPercentages',
            'totalReviews',
            'games',
            'categories'
        ));
    }

    public function showAuctions(Request $request) {
        $auctionsQuery = Auction::with(['product.shop.owner', 'highestBid.user'])
            ->whereHas('product', function($q) {
                $q->where('stok', '>', 0);
            })
            ->whereHas('product.shop.owner')
            ->where('end_time', '>', now())
            ->whereIn('status', ['running', 'pending']);

        if ($request->filled('search')) {
            $auctionsQuery->whereHas('product', function($q) use ($request) {
                $q->where('product_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->filled('game_id')) {
            $auctionsQuery->whereHas('product', function($q) use ($request) {
                $q->where('game_id', $request->game_id);
            });
        }

        if ($request->filled('category_id')) {
            $auctionsQuery->whereHas('product.category', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('min_price')) {
            $auctionsQuery->whereHas('product', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $auctionsQuery->whereHas('product', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        if ($request->filled('status')) {
            $auctionsQuery->where('status', $request->status);
        }

        $auctions = $auctionsQuery->orderBy('start_time', 'desc')->paginate(12);
        $categories = Category::orderBy('category_name')->get();
        $games = Game::orderBy('game_name')->get();

        return view('pages.user.auctions', compact('auctions', 'categories', 'games'));
    }

    public function showAuctionDetail($auctionId) {
        $auction = Auction::with(['product.shop.owner', 'highestBid.user'])
            ->whereHas('product', function($q) {
                $q->where('stok', '>', 0);
            })
            ->whereHas('product.shop.owner')
            ->where('auction_id', $auctionId)
            ->where('end_time', '>', now())
            ->whereHas('product.shop.owner')
            ->whereIn('status', ['running', 'pending'])
            ->first();

        if (!$auction) {
            return redirect()->route('user.home')->with('error', 'Lelang tidak ditemukan atau sudah berakhir.');
        }

        if ($auction->end_time <= now()) {
            Auction::where('auction_id', $auction->auction_id)->update(['status' => 'ended']);
            return redirect()->route('user.home')->with('error', 'Lelang sudah berakhir.');
        }

        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.auction_detail', compact('auction', 'categories'));
    }

    public function placeBid(Request $req, $auctionId) {

        $req->validate([
            'bid_price' => 'required|numeric',
        ]);

        $auction = Auction::with(['product.shop.owner', 'highestBid.user'])
            ->whereHas('product', function($q) {
                $q->where('stok', '>', 0);
            })
            ->whereHas('product.shop.owner')
            ->where('auction_id', $auctionId)
            ->where('end_time', '>', now())
            ->whereHas('product.shop.owner')
            ->whereIn('status', ['running'])
            ->first();

        if (!$auction) {
            return redirect()->route('user.home')->with('error', 'Lelang tidak ditemukan atau sudah berakhir.');
        }

        if ($auction->seller->user_id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menawar di lelang milik Anda sendiri.');
        }

        if ($auction->end_time <= now()) {
            Auction::where('auction_id', $auction->auction_id)->update(['status' => 'ended']);
            return redirect()->route('user.home')->with('error', 'Lelang sudah berakhir.');
        }

        if ($req->bid_price < $auction->current_price + 1000) {
            return redirect()->back()->with('error', 'Penawaran harus lebih tinggi dari harga saat ini.');
        }

        AuctionBid::create([
            'auction_id' => $auction->auction_id,
            'user_id' => Auth::id(),
            'bid_price' => $req->bid_price,
        ]);

        Auction::where('auction_id', $auction->auction_id)
            ->update(['current_price' => $req->bid_price]);

        // Logic to record the bid would go here

        return redirect()->back()->with('success', 'Penawaran berhasil ditempatkan.');
    }

    public function addToCart(AddToCartRequest $req, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);


        if ($product->stok < 1) {
            return back()->with('error', 'Produk tidak tersedia (stok habis).');
        }

        $product_shop = $product->shop;
        if ($product_shop->owner->user_id == $user->user_id) {
            return back()->with('error', 'Anda tidak dapat menambahkan produk dari toko Anda sendiri ke keranjang.');
        }

        $cart = $user->cart;

        $existingCartItem = $cart->cartItems()->where('product_id', $productId)->first();

        $requestedQty = $req->quantity;
        $currentQtyInCart = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalQty = $currentQtyInCart + $requestedQty;

        if ($totalQty > $product->stok) {
            $availableQty = $product->stok - $currentQtyInCart;

            if ($availableQty <= 0) {
                return back()->with('error', "Gagal menambahkan ke keranjang. Anda sudah memiliki {$currentQtyInCart} item di keranjang (stok maksimal: {$product->stok}).");
            }

            return back()->with('error', "Gagal menambahkan ke keranjang. Stok tidak cukup! Anda sudah memiliki {$currentQtyInCart} di keranjang. Maksimal hanya bisa menambah {$availableQty} lagi (total stok: {$product->stok}).");
        }

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $totalQty
            ]);

            return back()->with('success', "Berhasil memperbarui jumlah {$product->product_name} di keranjang menjadi {$totalQty} item.");
        }

        $cart->cartItems()->create([
            'product_id' => $productId,
            'quantity' => $requestedQty
        ]);

        return back()->with('success', "Berhasil menambahkan {$requestedQty} {$product->product_name} ke keranjang.");
    }

    public function updateCart(Request $req)
    {
         $req->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_items_id',
            'quantity'     => 'required|integer',
        ]);

        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang tidak ditemukan'
            ], 404);
        }

        $cartItem = $cart->cartItems()
            ->where('cart_items_id', $req->cart_item_id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item keranjang tidak ditemukan'
            ], 404);
        }

        if ($req->quantity <= 0) {
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'removed' => true
            ]);
        }

        $cartItem->quantity = $req->quantity;
        $cartItem->save();

        return response()->json([
            'success'  => true,
            'quantity' => $cartItem->quantity
        ]);
    }

    public function removeFromCart($cartItemId)
    {
        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return redirect()->route('user.cart')->with('error', 'Keranjang tidak ditemukan');
        }

        $cartItem = $cart->cartItems()
            ->where('cart_items_id', $cartItemId)
            ->first();

        if (!$cartItem) {
            return redirect()->route('user.cart')->with('error', 'Item keranjang tidak ditemukan');
        }

        $cartItem->delete();

        return redirect()->route('user.cart')->with('success', 'Item berhasil dihapus dari keranjang');
    }

}
