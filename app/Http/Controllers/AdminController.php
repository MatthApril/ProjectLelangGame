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
use App\Http\Requests\InsertTemplateRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Models\NotificationTemplate;
use App\Services\NotificationService;
use App\Mail\AccountBanned;
use App\Models\AdminSettings;
use App\Models\CartItem;
use App\Models\NotificationLog;
use App\Models\Complaint;
use App\Models\OrderItem;
use App\Models\ProductComment;
use App\Models\Refund;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // VIEW
    function showWithdraws() {
        $withdraws = Withdraw::with(['shop'])->orderBy('created_at', 'desc')->paginate(20);
        return view('pages.admin.withdraw', compact('withdraws'));
    }

    function processWithdraw(Request $req, $withdrawId) {
        $withdraw = Withdraw::with('shop')->where('status', 'waiting')->findOrFail($withdrawId);

        $req->validate([
            'action' => 'required|in:approve,reject'
        ]);

        if ($req->action === 'approve') {
            $withdraw->update(['status' => 'done']);
            $shop = $withdraw->shop;
            $shop->decrement('shop_balance', $withdraw->amount);
        } else {
            $withdraw->update(['status' => 'rejected']);
        }

        return redirect()->route('admin.withdraws.index')->with('success', 'Permintaan pencairan saldo berhasil diproses.');
    }

    function showDashboard() {
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalShops = Shop::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCategories = Category::count();
        $totalGames = Game::count();
        $totalRecipients = NotificationLog::sum('recipients_count');

        $admin_settings = AdminSettings::first();

        return view('pages.admin.dashboard', compact('totalUsers','totalSellers','totalShops','totalProducts','totalOrders','totalCategories','totalGames', 'totalRecipients', 'admin_settings'));
    }

    function updateSettings(Request $req) {
        $req->validate([
            'platform_fee_percentage' => 'required|integer|min:0|max:100',
        ]);

        $admin_settings = AdminSettings::first();
        if (!$admin_settings) {
            $admin_settings = new AdminSettings();
        }

        $admin_settings->update([
            'platform_fee_percentage' => $req->platform_fee_percentage,
        ]);

        return redirect()->back()->with('success', 'Pengaturan admin fee berhasil diperbarui.');
    }

    public function showCancelledOrders(Request $request)
    {
        $query = OrderItem::where('status', 'cancelled')
            ->with(['order.account', 'product', 'shop', 'complaint']);

        if ($request->filled('refund_status')) {
            if ($request->refund_status === 'refunded') {
                $query->where('is_refunded', true);
            } elseif ($request->refund_status === 'not_refunded') {
                $query->where('is_refunded', false);
            }
        }

        $cancelledOrders = $query->latest('paid_at')->paginate(20);

        $totalCancelled = OrderItem::where('status', 'cancelled')->count();
        $totalRefunded = OrderItem::where('status', 'cancelled')->where('is_refunded', true)->count();
        $totalNotRefunded = OrderItem::where('status', 'cancelled')->where('is_refunded', false)->count();

        return view('pages.admin.cancelled_orders', compact(
            'cancelledOrders',
            'totalCancelled',
            'totalRefunded',
            'totalNotRefunded'
        ));
    }

    public function showCancelledOrderDetail($orderItemId)
    {
        $orderItem = OrderItem::where('order_item_id', $orderItemId)
            ->where('status', 'cancelled')
            ->with(['order.account', 'product', 'shop', 'complaint.response'])
            ->firstOrFail();

        $categories = Category::orderBy('category_name')->get();

        return view('pages.admin.cancelled_order_detail', compact('orderItem', 'categories'));
    }

    public function markAsRefunded($orderItemId)
    {
        $orderItem = OrderItem::where('order_item_id', $orderItemId)
            ->where('status', 'cancelled')
            ->firstOrFail();

        if ($orderItem->is_refunded) {
            return back()->with('error', 'Pesanan ini sudah ditandai sebagai refunded.');
        }

        $orderItem->update(['is_refunded' => true]);

        return back()->with('success', 'Pesanan berhasil ditandai sebagai refunded oleh admin.');
    }

    public function undoRefunded($orderItemId)
    {
        $orderItem = OrderItem::where('order_item_id', $orderItemId)
            ->where('status', 'cancelled')
            ->firstOrFail();

        if (!$orderItem->is_refunded) {
            return back()->with('error', 'Pesanan ini belum ditandai sebagai refunded.');
        }

        $orderItem->update(['is_refunded' => false]);

        return back()->with('success', 'Status refund berhasil dibatalkan');
    }

    public function showComplaints(Request $request)
    {
        $query = Complaint::with(['orderItem.product', 'buyer', 'seller', 'response']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(20);

        // dd( $complaints);
        $categories = Category::orderBy('category_name')->get();

        return view('pages.admin.complaints', compact('complaints', 'categories'));
    }

    public function showComplaintDetail($complaintId)
    {
        $complaint = Complaint::where('complaint_id', $complaintId)
            ->with(['orderItem.product.shop', 'buyer', 'seller', 'response'])
            ->firstOrFail();

        $categories = Category::orderBy('category_name')->get();
        return view('pages.admin.complaint_detail', compact('complaint', 'categories'));
    }

    public function resolveComplaint(Request $request, $complaintId)
    {
        $request->validate([
            'decision' => 'required|in:refund,reject'
        ]);

        $complaint = Complaint::where('complaint_id', $complaintId)
            ->where('status', 'waiting_admin')
            ->firstOrFail();

        if ($request->decision === 'refund') {

            $buyer = $complaint->buyer;
            $buyer->increment('balance', $complaint->orderItem->subtotal);
            $shop = $complaint->orderItem->shop;
            $shop->decrement('running_transactions', $complaint->orderItem->subtotal);
            $complaint->orderItem->update([
                'status' => 'cancelled',
                'is_refunded' => false
            ]);

        } else {
            $orderItem = $complaint->orderItem;
            if ($orderItem->status === 'shipped') {
                $orderItem->update(['status' => 'completed']);

                $shop = $orderItem->shop;
                $shop->decrement('running_transactions', $orderItem->subtotal);
                $shop->increment('shop_balance', $orderItem->subtotal);
            }
        }
        $complaint->update([
            'status' => 'resolved',
            'decision' => $request->decision,
            'is_auto_resolved' => false,
            'resolved_at' => now()
        ]);
        $message = $request->decision === 'refund'
            ? 'Komplain disetujui. Buyer telah di-refund.'
            : 'Komplain ditolak.';
        return redirect()->route('admin.complaints.index')->with('success', $message);
    }

    function showUsers() {
        $users = User::withTrashed()->get();
        return view('pages.admin.users', compact('users'));
    }

    function showComments(Request $request)
    {
        $query = ProductComment::with(['product', 'user', 'orderItem'])
                    ->whereHas('product', function($q) {
                        $q->whereNull('deleted_at');
                    })
                    ->whereHas('user', function($q) {
                        $q->whereNull('deleted_at');
                    });

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $comments = $query->latest('created_at')->paginate(20);

        // dd($comments);
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
                'message' => 'Gagal menghapus komentar: ' . $e->getMessage() . '.'
            ], 500);
        }
    }

    function showCategories() {
        $categories = Category::withTrashed()->orderBy('category_name', 'asc')->get();
        $editCategory = null;

        return view('pages.admin.category', compact('categories', 'editCategory'));
    }

    function storeCategory(InsertCategoryRequest $request) {
        $validated = $request->validated();

        $imagePath = $request->file('category_img')->store('categories', 'public');

        Category::create([
            'category_name' => $validated['category_name'],
            'category_img' => $imagePath
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
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

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    function deleteCategory($category) {
        $categoryData = Category::findOrFail($category);

        $categoryData->delete();

        return redirect()->route('admin.categories.index')->with('success', "Kategori berhasil dihapus.");
    }

    function restoreCategory(Request $request) {
        $request->validate(['id' => 'required|exists:categories,category_id']);

        $category = Category::onlyTrashed()->findOrFail($request->id);
        $category->restore();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dan produk terkait berhasil dikembalikan.');
    }

    function showGames() {
        $games = Game::withTrashed()->with(['gamesCategories.category' => function($query) {$query->withTrashed();}])->paginate(15);
        $game = null;
        $categories = Category::all();
        $editGame = null;

        return view('pages.admin.game', compact('games', 'game', 'categories', 'editGame'));
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

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil ditambahkan.');
    }

    function showEditGame($id) {
        $game = Game::with(['gamesCategories' => function($query) {$query->whereHas('category', function($q) {$q->whereNull('deleted_at');})->with('category');}])->findOrFail($id);
        $categories = Category::all();
        $editGame = Game::findOrFail($id);
        return view('pages.admin.game', compact('game', 'categories', 'editGame'));
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

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil diupdate.');
    }

    function deleteGame($id) {
        $game = Game::findOrFail($id);

        $affectedProductsCount = $game->products()->count();

        $game->delete();

        return redirect()->route('admin.games.index')->with('success', "Game berhasil dihapus. {$affectedProductsCount} produk terkait juga dihapus (soft delete).");
    }

    function restoreGame(Request $request) {
        $request->validate(['id' => 'required|exists:games,game_id']);

        $game = Game::onlyTrashed()->findOrFail($request->id);
        $game->restore();
        return redirect()->back()->with('success', 'Game direstore.');
    }

    function showNotificationMaster(Request $request){
        $templates = NotificationTemplate::query()
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('code_tag', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('trigger_type', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%");
            });
        })
        ->paginate(6);
        return view('pages.admin.notif_master', compact('templates'));
    }

    function storeNotificationTemplate(InsertTemplateRequest $request){
        $validated = $request->validated();

        NotificationTemplate::create($validated);

        return redirect()->route('admin.notifications.index')->with('success', 'Template notifikasi berhasil ditambahkan.');
    }

    function showEditNotificationTemplate($id){
        $template = NotificationTemplate::findOrFail($id);
        $templates = NotificationTemplate::paginate(5);
        return view('pages.admin.notif_master', compact('template', 'templates'));
    }

    function updateNotificationTemplate(UpdateTemplateRequest $request, $id){
        $template = NotificationTemplate::findOrFail($id);
        $validated = $request->validated();

        $template->update($validated);

        return redirect()->route('admin.notifications.index')->with('success', 'Template notifikasi berhasil diupdate.');
    }

    function deleteNotificationTemplate($id){
        $template = NotificationTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Template notifikasi berhasil dihapus.');
    }

    function broadcastNotification(Request $req, $id){
        $template = NotificationTemplate::findOrFail($id);
        (new NotificationService())->broadcast($template->code_tag, $req->target_audience);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dibroadcast menggunakan template: ' . $template->code_tag . '.');
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
            CartItem::whereHas('product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->shop_id);
            })->delete();

            $shop->products()->delete();
        }

        Mail::to($user->email)->queue(new AccountBanned());

        if ($user->role == 'user') {
            $orderItems = OrderItem::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })->whereIn('status', ['pending', 'paid', 'shipped'])->get();

            foreach ($orderItems as $item) {
                if ($item->status == 'shipped') {
                    $item->update(['status' => 'completed']);
                    $shop = $item->shop;
                    $shop->decrement('running_transactions', $item->subtotal);
                    $shop->increment('shop_balance', $item->subtotal);
                    continue;
                }

                $item->update(['status' => 'cancelled']);
                $shop = $item->shop;
                $shop->decrement('running_transactions', $item->subtotal);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibanned');
    }

    function unbanUser(Request $req) {
        $req->validate([
            'id' => 'required',
        ]);

        $id = $req->input('id');
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diunbanned.');
    }
}
