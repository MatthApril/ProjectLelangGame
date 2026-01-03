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
        'status',
        'shipped_at',
        'paid_at',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
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
}
