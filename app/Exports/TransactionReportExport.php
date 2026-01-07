<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class TransactionReportExport implements FromCollection, WithHeadings
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items->map(function ($item) {
            return [
                $item->paid_at,
                $item->order_id,
                $item->order->account->username,
                $item->product->product_name ?? '-',
                $item->quantity,
                $item->subtotal,
                $item->status
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Order ID',
            'Pembeli',
            'Produk',
            'Qty',
            'Subtotal',
            'Status'
        ];
    }
}
