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
        'status'
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
}
