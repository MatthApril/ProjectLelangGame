<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'shop_id',
        'product_name',
        'product_img',
        'stok',
        'price',
        'rating',
        'category_id',
        'game_id'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id');
    }
}
