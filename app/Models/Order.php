<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'shop_id',
        'total_prices',
        'status'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'user_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
