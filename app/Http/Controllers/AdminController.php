<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\GameCategory;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\InsertCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\InputGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Mail\AccountBanned;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // VIEW
    function showDashboard() {
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalShops = Shop::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCategories = Category::count();
        $totalGames = Game::count();

        return view('pages.admin.dashboard', compact('totalUsers','totalSellers','totalShops','totalProducts','totalOrders','totalCategories','totalGames'));
    }

    function showUsers() {
        $users = User::withTrashed()->get();
        return view('pages.admin.users', compact('users'));
    }
    function showComments(Request $request)
    {
        $query = ProductComment::with(['product', 'user', 'orderItem']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $comments = $query->latest('created_at')->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('partials.comments_table', compact('comments'))->render(),
                'pagination' => $comments->links()->toHtml()
            ]);
        }

        return view('pages.admin.comments', compact('comments'));
    }

    function deleteComment($id)
    {
        try {
            $comment = ProductComment::findOrFail($id);
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus komentar: ' . $e->getMessage()
            ], 500);
        }
    }
    function showCategories() {
        $categories = Category::orderBy('category_name', 'asc')->get();
        $editCategory = null;

        return view('pages.admin.category', compact('categories', 'editCategory'));
    }

    function storeCategory(InsertCategoryRequest $request) {
        $validated = $request->validated();

        Category::create([
            'category_name' => $validated['category_name']
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    function showEditCategory($id) {
        $categories = Category::orderBy('category_name', 'asc')->get();
        $editCategory = Category::findOrFail($id);

        return view('pages.admin.category', compact('categories', 'editCategory'));
    }

    function updateCategory(UpdateCategoryRequest $request, $id) {
        $category = Category::findOrFail($id);
        $validated = $request->validated();

        $category->update([
            'category_name' => $validated['category_name']
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate');
    }

    function deleteCategory($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus');
    }

    function showGames() {
        $games = Game::with(['gamesCategories.category' => function($query) {$query->withTrashed();}])->paginate(15);
        return view('pages.admin.game', compact('games'));
    }

    function showCreateGame() {
        $game = null;
        $categories = Category::all();
        return view('pages.admin.create_game', compact('game', 'categories'));
    }

    function storeGame(InputGameRequest $request) {
        $validated = $request->validated();

        $imagePath = $request->file('game_img')->store('games', 'public');

        $game = Game::create([
            'game_name' => $validated['game_name'],
            'game_img' => $imagePath
        ]);

        foreach ($validated['categories'] as $categoryId) {
            GameCategory::create([
                'game_id' => $game->game_id,
                'category_id' => $categoryId
            ]);
        }

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil ditambahkan');
    }

    function showEditGame($id) {
        $game = Game::with(['gamesCategories' => function($query) {$query->whereHas('category', function($q) {$q->whereNull('deleted_at');})->with('category');}])->findOrFail($id);
        $categories = Category::all();
        return view('pages.admin.create_game', compact('game', 'categories'));
    }

    function updateGame(UpdateGameRequest $request, $id) {
        $game = Game::findOrFail($id);
        $validated = $request->validated();

        $game->update(['game_name' => $validated['game_name']]);

        if ($request->hasFile('game_img')) {
            if ($game->game_img) {
                Storage::disk('public')->delete($game->game_img);
            }
            $imagePath = $request->file('game_img')->store('games', 'public');
            $game->update(['game_img' => $imagePath]);
        }

         GameCategory::where('game_id', $id)->whereHas('category', function($query) {$query->whereNull('deleted_at');})->delete();

        foreach ($validated['categories'] as $categoryId) {
            GameCategory::firstOrCreate([
                'game_id' => $game->game_id,
                'category_id' => $categoryId
            ]);
        }

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil diupdate');
    }

    function deleteGame($id) {
        $game = Game::findOrFail($id);
        if ($game->game_img) {
            Storage::disk('public')->delete($game->game_img);
        }

        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus');
    }

    function banUser(Request $req) {
        $req->validate([
            'id' => 'required',
        ]);

        if ($req->input('id') == Auth::user()->user_id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat memban diri sendiri.');
        }

        if ($req->input('id') == 1) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat memban admin utama.');
        }

        $id = $req->input('id');
        $user = User::findOrFail($id);
        $user->delete();

        $shop = Shop::where('owner_id', $user->user_id)->first();
        if ($shop) {
            $shop->products()->delete();
        }

        Mail::to($user->email)->queue(new AccountBanned());

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibanned');
    }

    function unbanUser(Request $req) {
        $req->validate([
            'id' => 'required',
        ]);

        $id = $req->input('id');
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diunbanned');
    }
}
