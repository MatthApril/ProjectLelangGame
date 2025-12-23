<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Game;
use App\Http\Requests\InsertProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    // VIEW
    function showDashboard() {
        return view('pages.seller.dashboard');
    }

    public function index(Request $request)
    {
        $query = Product::where('shop_id', Auth::user()->shop->shop_id)
            ->with(['game', 'category']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();
        $categories = Category::all();

        return view('pages.seller.product', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $games = Game::all();
        $product = null;

        return view('pages.seller.create', compact('categories', 'games', 'product'));
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
        $categories = Category::all();
        $games = Game::all();

        return view('pages.seller.create', compact('product', 'categories', 'games'));
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
}
