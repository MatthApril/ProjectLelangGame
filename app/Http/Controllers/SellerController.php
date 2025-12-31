<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Game;
use App\Http\Requests\InsertProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductComment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $query = Product::where('shop_id', Auth::user()->shop->shop_id)->with(['game', 'category']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();
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
        $games = Game::all();
        $categories = Category::orderBy('category_name')->get();

        return view('pages.seller.create', compact('product', 'games', 'categories'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::where('shop_id', Auth::user()->shop->shop_id)->findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('product_img')) {
            // Delete old image
            if ($product->product_img) {
                Storage::disk('public')->delete($product->product_img);
            }

            // Upload new image
            $sellerId = Auth::user()->shop->shop_id;
            $validated['product_img'] = $request->file('product_img')->store("seller/{$sellerId}/products", 'public');
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::where('shop_id', Auth::user()->shop->shop_id)->findOrFail($id);

        if ($product->product_img) {
            Storage::disk('public')->delete($product->product_img);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function getCategoriesByGame($gameId)
    {
        $categories = Game::findOrFail($gameId)->gamesCategories()->whereHas('category', function($query) {    $query->whereNull('deleted_at'); })->with('category')->get()->pluck('category')->filter();

        return response()->json($categories);
    }

    // public function trade(){
    //     return view('pages.seller.trade');
    // }
}
