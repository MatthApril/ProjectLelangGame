<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'shop_id',
        'product_price',
        'subtotal',
        'quantity',
        'admin_fee',
        'status',
        'shipped_at',
        'paid_at',
        'is_refunded'
    ];
    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'is_refunded' => 'boolean'
    ];

    public function complaint()
    {
        return $this->hasOne(Complaint::class, 'order_item_id', 'order_item_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id')->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function comment()
    {
        return $this->hasOne(ProductComment::class,'order_item_id');
    }

    public function hasReview()
    {
        return $this->comment()->exists();
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
    public function canAutoComplete()
    {
        return $this->isShipped()
            && $this->shipped_at
            && $this->shipped_at->addDays(3)->isPast();
    }

    public function getCancelReason(): string
    {
        if (!$this->isCancelled()) {
            return '-';
        }

        $complaint = $this->complaint;

        if ($complaint && $complaint->decision === 'refund') {
            return 'Komplain Disetujui (Refund)';
        }

        return 'Dibatalkan oleh Seller';
    }
}
