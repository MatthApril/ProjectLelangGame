<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeReportAdmin implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders->map(function ($order, $index) {
            $netIncome = $order->total_prices - $order->admin_fee;
            
            return [
                $index + 1,
                $order->order_date,
                $order->order_id,
                $order->account->username,
                $order->orderItems->count(),
                $order->total_prices,
                $order->admin_fee,
                $netIncome
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Order ID',
            'Pembeli',
            'Jumlah Item',
            'Total Transaksi',
            'Admin Fee',
            'Pendapatan Bersih'
        ];
    }
}