<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;
    Protected $table = 'shops';
    protected $primaryKey = 'shop_id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'shop_name',
        'owner_id',
        'shop_rating',
        'shop_img',
        'open_hour',
        'running_transactions', // Saldo yang masih dalam proses (belum bisa dicairkan)
        'shop_balance',// Saldo yang sudah bisa dicairkan
        'close_hour',
        'status'
    ];
    protected $casts = [
        'shop_rating' => 'float',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    
}
