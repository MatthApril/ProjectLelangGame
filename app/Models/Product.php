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
        'description',
        'product_img',
        'stok',
        'price',
        'rating',
        'category_id',
        'game_id',
        'type'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id')->withTrashed();
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id')->withTrashed();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id', 'product_id');
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'product_id', 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at')
            ->whereHas('category', function($q) {
                $q->whereNull('deleted_at');
            })
            ->whereHas('game', function($q) {
                $q->whereNull('deleted_at');
            });
    }

    public function isAvailableForPurchase()
    {
        return !$this->deleted_at && !$this->category?->deleted_at && !$this->game?->deleted_at && $this->stok > 0;
    }

    public function isInactiveByRelation()
    {
        return !$this->deleted_at && ($this->category?->deleted_at || $this->game?->deleted_at);
    }
}
