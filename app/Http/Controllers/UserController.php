<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Game;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // VIEW
    public function showCart()
    {
        $user = Auth::user();
        $user_cart = $user->cart()->first();
        $cartItems = $user_cart
                ? $user_cart->cartItems()
                    ->whereHas('product', function ($q) {
                        $q->whereNull('deleted_at');
                    })
                    ->with('product')
                    ->get()
                : collect();

        return view('pages.user.cart', compact('cartItems'));
    }

    public function showOrders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('shop')->get();

        return view('pages.user.my_order', compact('orders'));
    }

    public function showOrderDetail($orderId)
    {
        $user = Auth::user();
        $order = $user->orders()
                ->with([
                    'orderItems.product' => function ($query) {
                        $query->withTrashed();
                    },
                    'shop'
                ])
                ->where('order_id', $orderId)
                ->firstOrFail();

        return view('pages.user.order_detail', compact('order'));
    }

    public function showCartPartial()
    {
        $user = Auth::user();
        $user_cart = $user->cart()->first();
        $cartItems = $user_cart
            ? $user_cart->cartItems()->with('product')->get()
            : collect();

        return response()->json([
            'html' => view('pages.user.cart-partial', compact('cartItems'))->render()
        ]);
    }

    function showHome() {
        $owners = User::where('role', 'seller')->get();
        $featuredGames = Game::withCount(['products' => function($query) {
                $query->whereHas('shop', function($q) {
                    $q->where('status', 'open');
                });
                if(Auth::check() && Auth::user()->role === 'seller' && Auth::user()->shop){
                    $query->where('shop_id', '!=', Auth::user()->shop->shop_id);
                }
            }])->orderBy('products_count', 'desc')->take(6)->get();

        $latestProductsQuery = Product::with(['game', 'shop', 'category'])
            ->whereHas('category', function($query) {
                $query->whereNull('deleted_at');
            })->whereHas('shop', function($query) {
                $query->where('status', 'open');
            })->where('stok', '>', 0);
            if(Auth::check() && Auth::user()->role === 'seller' && Auth::user()->shop){
                $latestProductsQuery->where('shop_id', '!=', Auth::user()->shop->shop_id);
            }
        $latestProducts = $latestProductsQuery->latest()->take(12)->get();
        $topShopsQuery = Shop::where('status', 'open');

        if(Auth::check() && Auth::user()->role === 'seller' && Auth::user()->shop){
            $topShopsQuery->where('shop_id','!=',Auth::user()->shop->shop_id);
        }
        $topShops= $topShopsQuery->orderBy('shop_rating', 'desc')->take(6)->get();

        return view('pages.user.home', compact('featuredGames', 'latestProducts', 'topShops', 'owners'));
    }

    public function showGames(Request $request)
    {
        $query = Game::withCount(['products'=> function($q){
            $q->whereHas('shop',function($query){
                $query->where('status','open');
            });
            if(Auth::check()&&Auth::user()->role === 'seller'&&Auth::user()->shop){
                $q->where('shop_id','!=',Auth::user()->shop->shop_id);
            }
        }]);


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

        $productsQuery = Product::where('game_id', $game->game_id)->with(['shop', 'category'])
            ->whereHas('shop', function($query) {
                $query->where('status', 'open');
            })
            ->where('stok', '>', 0);
        if(Auth::check() && Auth::user()->role === 'seller' && Auth::user()->shop){
            $productsQuery->where('shop_id','!=',Auth::user()->shop->shop_id);
        }
        $products=$productsQuery->latest()->paginate(12);

        return view('pages.user.game_detail', compact('game', 'categories', 'products'));
    }

    public function showProducts(Request $request)
    {
        $query = Product::with(['game', 'shop', 'category'])
            ->whereHas('shop', function($q) {
                $q->where('status', 'open');
            })
            ->where('stok', '>', 0);

        if (Auth::check() && Auth::user()->role === 'seller' && Auth::user()->shop){
            $query->where('shop_id','!=',Auth::user()->shop->shop_id);
        }
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

        $relatedProductsQuery = Product::where('game_id', $product->game_id)
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->where('stok', '>', 0);

        if (Auth::check() && Auth::user()->role === 'seller' && Auth::user()->shop) {
            $relatedProductsQuery->where('shop_id', '!=', Auth::user()->shop->shop_id);
        }
        $relatedProducts = $relatedProductsQuery->take(12)->get();

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

    public function addToCart(AddToCartRequest $req, $productId)
    {
        $req->validated();

        $user = Auth::user();

        $cart = $user->cart()->firstOrCreate([]);

        $qty = $req->quantity;

        $cartItem = $cart->cartItems()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $qty);
        } else {
            $cart->cartItems()->create([
                'product_id' => $productId,
                'quantity'   => $qty,
            ]);
        }

        return redirect()->route('user.cart')->with('success', 'Product added to cart.');
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
                'message' => 'Cart not found'
            ], 404);
        }

        $cartItem = $cart->cartItems()
            ->where('cart_items_id', $req->cart_item_id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
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
            return redirect()->route('user.cart')->with('error', 'Cart not found');
        }

        $cartItem = $cart->cartItems()
            ->where('cart_items_id', $cartItemId)
            ->first();

        if (!$cartItem) {
            return redirect()->route('user.cart')->with('error', 'Cart item not found');
        }

        $cartItem->delete();

        return redirect()->route('user.cart')->with('success', 'Item removed from cart');
    }

    public function topup()
    {
        return view('pages.user.topup');
    }

    public function joki()
    {
        return view('pages.user.joki');
    }

    public function akun()
    {
        return view('pages.user.akun');
    }

    public function item()
    {
        return view('pages.user.item');
    }
}
