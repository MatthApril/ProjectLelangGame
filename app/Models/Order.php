<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'snap_token',
        'expire_payment_at',
        'admin_fee',
        'total_prices',
    ];

    public function account()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
