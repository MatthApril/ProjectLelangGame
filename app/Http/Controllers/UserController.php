<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Game;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // VIEW
    function showHome() {
        $owners = User::where('role', 'seller')->get();
        $featuredGames = Game::withCount(['products' => function($query) {
                $query->whereHas('shop', function($q) {
                    $q->where('status', 'open');
                });
            }])->orderBy('products_count', 'desc')->take(6)->get();

        $latestProducts = Product::with(['game', 'shop', 'category'])
            ->whereHas('category', function($query) {
                $query->whereNull('deleted_at');
            })->whereHas('shop', function($query) {
                $query->where('status', 'open');
            })->where('stok', '>', 0)->latest()->take(12)->get();

        $topShops = Shop::where('status', 'open')->orderBy('shop_rating', 'desc')->take(6)->get();
        
        return view('pages.user.home', compact('featuredGames', 'latestProducts', 'topShops', 'owners'));
    }

     public function showGames(Request $request)
    {
        $query = Game::withCount('products');

        if ($request->filled('search')) {
            $query->where('game_name', 'LIKE', '%' . $request->search . '%');
        }

        $games = $query->orderBy('game_name', 'asc')->paginate(12);

        return view('pages.user.games', compact('games'));
    }

    public function showGameDetail($id)
    {
        $game = Game::with(['gamesCategories.category' => function($query) {
                $query->whereNull('deleted_at');
            }])->findOrFail($id);

        $categories = $game->gamesCategories()
            ->whereHas('category', function($query) {
                $query->whereNull('deleted_at');
            })->with('category')->get()->pluck('category')->filter();

        $products = Product::where('game_id', $game->game_id)->with(['shop', 'category'])
            ->whereHas('shop', function($query) {
                $query->where('status', 'open');
            })
            ->where('stok', '>', 0)->latest()->paginate(12);

        return view('pages.user.game_detail', compact('game', 'categories', 'products'));
    }

    public function showProducts(Request $request)
    {
        $query = Product::with(['game', 'shop', 'category'])
            ->whereHas('shop', function($q) {
                $q->where('status', 'open');
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
        $product = Product::with(['game', 'shop', 'category', 'comments.user'])->findOrFail($id);

        $relatedProducts = Product::where('game_id', $product->game_id)
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->where('stok', '>', 0)
            ->take(12)
            ->get();

        return view('pages.user.product_detail', compact('product', 'relatedProducts'));
    }

    public function showShop($id)
    {
        $shop = Shop::with('owner')->findOrFail($id);

        $products = Product::where('shop_id', $shop->shop_id)
            ->with(['game', 'category'])
            ->where('stok', '>', 0)
            ->latest()
            ->paginate(12);

        return view('pages.user.shop_detail', compact('shop', 'products'));
    }


}
