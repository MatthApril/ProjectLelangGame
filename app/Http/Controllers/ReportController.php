<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Shop;
use App\Models\Order;
use App\Models\AdminSettings;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

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

        $orders = Order::whereBetween('order_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->where('status', 'paid')
            ->with(['account', 'orderItems.product.shop'])
            ->orderBy('order_date', 'desc')
            ->get();

        $totalTransactions = $orders->count();
        $completedOrders = $orders->count();
        $totalRevenue = $orders->sum('total_prices');
        $totalAdminFee = $orders->sum('admin_fee');

        return view('pages.report.admin.income_report', compact(
            'orders',
            'totalTransactions',
            'completedOrders',
            'totalRevenue',
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

        $orders = Order::whereBetween('order_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->where('status', 'paid')
            ->with(['account', 'orderItems.product.shop'])
            ->orderBy('order_date', 'desc')
            ->get();

        $totalTransactions = $orders->count();
        $completedOrders = $orders->count();
        $totalRevenue = $orders->sum('total_prices');
        $totalAdminFee = $orders->sum('admin_fee');

        $pdf = Pdf::loadView('pages.report.pdf_ui.income_pdf', [
            'orders' => $orders,
            'totalTransactions' => $totalTransactions,
            'completedOrders' => $completedOrders,
            'totalRevenue' => $totalRevenue,
            'totalAdminFee' => $totalAdminFee,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Pendapatan-Platform-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportIncomeExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $orders = Order::whereBetween('order_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->where('status', 'paid')
            ->with(['account', 'orderItems.product.shop'])
            ->orderBy('order_date', 'desc')
            ->get();

        return Excel::download(
            new \App\Exports\IncomeReportAdmin($orders),
            'Laporan-Pendapatan-Platform-' . now()->format('YmdHis') . '.xlsx'
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

    public function showTopSellerReport()
    {
        return view('pages.report.admin.topseller_report');
    }

    public function generateTopSellerReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'top_limit' => 'required|in:10,15,20',
        ]);

        $topLimit = $request->top_limit;
        $adminFeePercentage = AdminSettings::first()->platform_fee_percentage ?? 0;

        $topSellers = OrderItem::select(
                'shop_id',
                 DB::raw('COUNT(*) as total_transactions'),
                 DB::raw('SUM(quantity) as total_quantity'),
                 DB::raw('SUM(subtotal) as total_revenue')
            )
            ->where('status', 'completed')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->groupBy('shop_id')
            ->orderByDesc('total_revenue')
            ->limit($topLimit)
            ->get()
            ->map(function($item) use ($adminFeePercentage) {
                $shop = Shop::with('owner')->find($item->shop_id);
                
                if (!$shop) {
                    return null;
                }

                $adminFee = round($item->total_revenue * ($adminFeePercentage / 100));
                $netIncome = $item->total_revenue - $adminFee;

                return (object) [
                    'shop_id' => $item->shop_id,
                    'shop_name' => $shop->shop_name,
                    'owner' => $shop->owner,
                    'total_transactions' => $item->total_transactions,
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => $item->total_revenue,
                    'admin_fee' => $adminFee,
                    'net_income' => $netIncome
                ];
            })
            ->filter();

        $totalRevenue = $topSellers->sum('total_revenue');
        $totalTransactions = $topSellers->sum('total_transactions');
        $totalAdminFee = $topSellers->sum('admin_fee');

        return view('pages.report.admin.topseller_report', compact(
            'topSellers',
            'totalRevenue',
            'totalTransactions',
            'totalAdminFee',
            'topLimit',
            'adminFeePercentage',
            'request'
        ));
    }

    public function exportTopSellerPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'top_limit' => 'required|in:10,15,20',
        ]);

        $topLimit = $request->top_limit;
        $adminFeePercentage = AdminSettings::first()->platform_fee_percentage ?? 0;

        $topSellers = OrderItem::select(
                'shop_id',
                 DB::raw('COUNT(*) as total_transactions'),
                 DB::raw('SUM(quantity) as total_quantity'),
                 DB::raw('SUM(subtotal) as total_revenue')
            )
            ->where('status', 'completed')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->groupBy('shop_id')
            ->orderByDesc('total_revenue')
            ->limit($topLimit)
            ->get()
            ->map(function($item) use ($adminFeePercentage) {
                $shop = Shop::with('owner')->find($item->shop_id);
                
                if (!$shop) {
                    return null;
                }

                $adminFee = round($item->total_revenue * ($adminFeePercentage / 100));
                $netIncome = $item->total_revenue - $adminFee;

                return (object) [
                    'shop_id' => $item->shop_id,
                    'shop_name' => $shop->shop_name,
                    'owner' => $shop->owner,
                    'total_transactions' => $item->total_transactions,
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => $item->total_revenue,
                    'admin_fee' => $adminFee,
                    'net_income' => $netIncome
                ];
            })
            ->filter();

        $totalRevenue = $topSellers->sum('total_revenue');
        $totalTransactions = $topSellers->sum('total_transactions');
        $totalAdminFee = $topSellers->sum('admin_fee');

        $pdf = Pdf::loadView('pages.report.pdf_ui.topseller_pdf', [
            'topSellers' => $topSellers,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'totalAdminFee' => $totalAdminFee,
            'topLimit' => $topLimit,
            'adminFeePercentage' => $adminFeePercentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Top-' . $topLimit . '-Seller-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportTopSellerExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'top_limit' => 'required|in:10,15,20',
        ]);

        $topLimit = $request->top_limit;
        $adminFeePercentage = AdminSettings::first()->platform_fee_percentage ?? 0;

        $topSellers = OrderItem::select(
                'shop_id',
                 DB::raw('COUNT(*) as total_transactions'),
                 DB::raw('SUM(quantity) as total_quantity'),
                 DB::raw('SUM(subtotal) as total_revenue')
            )
            ->where('status', 'completed')
            ->whereBetween('paid_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ])
            ->groupBy('shop_id')
            ->orderByDesc('total_revenue')
            ->limit($topLimit)
            ->get()
            ->map(function($item) use ($adminFeePercentage) {
                $shop = Shop::with('owner')->find($item->shop_id);
                
                if (!$shop) {
                    return null;
                }

                $adminFee = round($item->total_revenue * ($adminFeePercentage / 100));
                $netIncome = $item->total_revenue - $adminFee;

                return (object) [
                    'shop_id' => $item->shop_id,
                    'shop_name' => $shop->shop_name,
                    'owner' => $shop->owner,
                    'total_transactions' => $item->total_transactions,
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => $item->total_revenue,
                    'admin_fee' => $adminFee,
                    'net_income' => $netIncome
                ];
            })
            ->filter();

        return Excel::download(
            new \App\Exports\TopSellerReportAdmin($topSellers, $topLimit, $adminFeePercentage),
            'Laporan-Top-' . $topLimit . '-Seller-' . now()->format('YmdHis') . '.xlsx'
        );
    }
}
