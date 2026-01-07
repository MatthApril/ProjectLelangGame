<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionReportAdmin implements FromCollection, WithHeadings
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items->map(function ($item, $index) {
            return [
                $index + 1,
                $item->paid_at ? $item->paid_at->format('d/m/Y H:i') : '-',
                $item->order_id,
                $item->order->account->username,
                $item->product->product_name ?? 'Produk Dihapus',
                $item->quantity,
                $item->subtotal,
                $this->getStatusText($item->status)
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
            'Produk',
            'Qty',
            'Subtotal',
            'Status'
        ];
    }

    private function getStatusText($status)
    {
        return match($status) {
            'paid' => 'Dibayar',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $status
        };
    }
}