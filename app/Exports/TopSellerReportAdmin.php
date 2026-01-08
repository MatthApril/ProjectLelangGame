<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TopSellerReportAdmin implements FromCollection, WithHeadings
{
    protected $sellers;
    protected $topLimit;
    protected $adminFeePercentage;

    public function __construct($sellers, $topLimit, $adminFeePercentage)
    {
        $this->sellers = $sellers;
        $this->topLimit = $topLimit;
        $this->adminFeePercentage = $adminFeePercentage;
    }

    public function collection()
    {
        return $this->sellers->map(function ($shop, $index) {
            return [
                $index + 1,
                $shop->shop_name,
                $shop->owner->username,
                $shop->total_transactions,
                $shop->total_revenue,
                $shop->admin_fee,
                $shop->net_income
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'Nama Toko',
            'Pemilik',
            'Total Transaksi',
            'Total Pendapatan',
            'Admin Fee',
            'Pendapatan Bersih'
        ];
    }
}