<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComment extends Model
{
    use SoftDeletes;
    protected $table = 'products_comments';
    protected $primaryKey = 'comment_id';
    public $timestamps = true;
    public $incrementing = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_item_id',
        'comment',
        'rating'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class,'order_item_id');
    }

    protected static function booted()
    {
        static::created(function ($comment) {
            $comment->updateProductRating();
        });

        static::deleted(function ($comment) {
            $comment->updateProductRating();
        });

    }

    public function updateProductRating()
    {
        $product = $this->product;

        $avgRating = $product->comments()->avg('rating');
        $product->rating = $avgRating ? round($avgRating, 2) : 0;
        $product->save();

        $shop = $product->shop;
        $shopAvgRating = $shop->products() ->where('rating', '>', 0)->avg('rating');
        $shop->shop_rating = $shopAvgRating ? round($shopAvgRating, 2) : 0;
        $shop->save();
    }
}
