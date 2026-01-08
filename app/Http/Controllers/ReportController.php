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
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // Admin
    public function showSellerTransactionReport()
    {
        $sellers = User::where('role', 'seller')
            ->whereHas('shop')
            ->with('shop')
            ->orderBy('username')
            ->get();

        return view('pages.report.admin.transaction_report_seller', [
            'sellers' => $sellers,
            'request' => request()
        ]);
    }

    public function generateSellerTransactionReport(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $seller = User::with('shop')->findOrFail($request->seller_id);
        $shop = $seller->shop;

        if (!$shop) {
            return back()->with('error', 'Seller tidak memiliki toko.');
        }

        $query = OrderItem::where('shop_id', $shop->shop_id)
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product']);

        $orderItems = $query->get();

        $totalTransactions = $orderItems->count();
        $totalRevenue = $orderItems->where('status', 'completed')->sum('subtotal');
        $totalPending = $orderItems->whereIn('status', ['paid', 'shipped'])->sum('subtotal');
        $totalCancelled = $orderItems->where('status', 'cancelled')->count();

        $sellers = User::where('role', 'seller')
            ->whereHas('shop')
            ->with('shop')
            ->orderBy('username')
            ->get();

        return view('pages.report.admin.transaction_report_seller', compact(
            'orderItems',
            'totalTransactions',
            'totalRevenue',
            'totalPending',
            'totalCancelled',
            'sellers',
            'seller',
            'shop',
            'request'
        ));
    }

    public function exportSellerPdf(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $seller = User::with('shop')->findOrFail($request->seller_id);
        $shop = $seller->shop;

        $orderItems = OrderItem::where('shop_id', $shop->shop_id)
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product'])
            ->get();

        $totalTransactions = $orderItems->count();
        $totalRevenue = $orderItems->where('status', 'completed')->sum('subtotal');
        $totalPending = $orderItems->whereIn('status', ['paid', 'shipped'])->sum('subtotal');
        $totalCancelled = $orderItems->where('status', 'cancelled')->count();

        $pdf = Pdf::loadView('pages.report.pdf_ui.t_report_seller_pdf', [
            'orderItems' => $orderItems,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'totalPending' => $totalPending,
            'totalCancelled' => $totalCancelled,
            'seller' => $seller,
            'shop' => $shop,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return $pdf->download('Laporan-Transaksi-Seller-' . $shop->shop_name . '-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportSellerExcel(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $seller = User::with('shop')->findOrFail($request->seller_id);
        $shop = $seller->shop;

        $orderItems = OrderItem::where('shop_id', $shop->shop_id)
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product'])
            ->get();

        return Excel::download(
            new \App\Exports\TransactionReportAdmin($orderItems),
            'Laporan-Transaksi-Seller-' . $shop->shop_name . '-' . now()->format('YmdHis') . '.xlsx'
        );
    }

    public function showIncomeReport()
    {
        return view('pages.report.admin.income_report');
    }

    public function generateIncomeReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $query = OrderItem::whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product.shop']);

        $orderItems = $query->get();

        $totalAdminFee = Order::whereHas('orderItems', function($q) use ($request) {
            $q->where('status', 'completed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        })->sum('admin_fee');

        $totalTransactions = $orderItems->count();
        $completedOrders = $orderItems->where('status', 'completed')->count();
        $totalRevenue = $orderItems->where('status', 'completed')->sum('subtotal');
        $totalPending = $orderItems->whereIn('status', ['paid', 'shipped'])->sum('subtotal');
        $totalCancelled = $orderItems->where('status', 'cancelled')->count();

        return view('pages.report.admin.income_report', compact(
            'orderItems',
            'totalTransactions',
            'completedOrders',
            'totalRevenue',
            'totalPending',
            'totalCancelled',
            'totalAdminFee',
            'request'
        ));
    }

    public function exportIncomePdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $orderItems = OrderItem::whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product.shop'])
            ->get();

        $totalAdminFee = Order::whereHas('orderItems', function($q) use ($request) {
            $q->where('status', 'completed')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        })->sum('admin_fee');

        $totalTransactions = $orderItems->count();
        $completedOrders = $orderItems->where('status', 'completed')->count();
        $totalRevenue = $orderItems->where('status', 'completed')->sum('subtotal');
        $totalPending = $orderItems->whereIn('status', ['paid', 'shipped'])->sum('subtotal');
        $totalCancelled = $orderItems->where('status', 'cancelled')->count();

        $pdf = Pdf::loadView('pages.report.pdf_ui.income_pdf', [
            'orderItems' => $orderItems,
            'totalTransactions' => $totalTransactions,
            'completedOrders' => $completedOrders,
            'totalRevenue' => $totalRevenue,
            'totalPending' => $totalPending,
            'totalCancelled' => $totalCancelled,
            'totalAdminFee' => $totalAdminFee,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return $pdf->download('Laporan-Pendapatan-Platform-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportIncomeExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $orderItems = OrderItem::whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product.shop'])
            ->get();

        return Excel::download(
            new \App\Exports\IncomeReportAdmin($orderItems),
            'laporan-pendapatan-platform.xlsx'
        );
    }

        public function showTransactionReport()
    {
        $shop = Auth::user()->shop;
        $categories = Category::orderBy('category_name')->get();

        return view('pages.report.seller.transaction_report', compact('shop', 'categories'));
    }

    // Seller
    public function generateTransactionReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,paid,shipped,completed,cancelled'
        ]);

        $shop = Auth::user()->shop;

        $query = OrderItem::where('shop_id', $shop->shop_id)
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product']);

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orderItems = $query->orderBy('paid_at', 'desc')->get();

        $totalTransactions = $orderItems->count();
        $totalRevenue = $orderItems->where('status', 'completed')->sum('subtotal');
        $totalPending = $orderItems->whereIn('status', ['paid', 'shipped'])->sum('subtotal');
        $totalCancelled = $orderItems->where('status', 'cancelled')->count();

        $categories = Category::orderBy('category_name')->get();

        return view('pages.report.seller.transaction_report', compact(
            'shop',
            'categories',
            'orderItems',
            'totalTransactions',
            'totalRevenue',
            'totalPending',
            'totalCancelled',
            'request'
        ));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,paid,shipped,completed,cancelled'
        ]);

        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('pages.report.pdf_ui.t_report_seller_pdf', $data)
            ->setPaper('A4', 'landscape');

        return $pdf->download(
            'Laporan-Transaksi-' . now()->format('Y-m-d_H-i-s') . '.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,paid,shipped,completed,cancelled'
        ]);

        $data = $this->getReportData($request);

        return Excel::download(
            new \App\Exports\TransactionReportExport($data['orderItems']),
            'Laporan-Transaksi-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    private function getReportData(Request $request)
    {
        $shop = Auth::user()->shop;

        $query = OrderItem::where('shop_id', $shop->shop_id)
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->with(['order.account', 'product']);

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orderItems = $query->orderBy('paid_at', 'desc')->get();

        $totalTransactions = $orderItems->count();
        $totalRevenue = $orderItems->where('status', 'completed')->sum('subtotal');
        $totalPending = $orderItems->whereIn('status', ['paid', 'shipped'])->sum('subtotal');
        $totalCancelled = $orderItems->where('status', 'cancelled')->count();

        return [
            'orderItems' => $orderItems,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'totalPending' => $totalPending,
            'totalCancelled' => $totalCancelled,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'shop' => $shop
        ];
    }
}
