<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $table = 'auctions';
    protected $primaryKey = 'auction_id';
    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'seller_id',
        'start_price',
        'current_price',
        'start_time',
        'end_time',
        'status',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function bids()
    {
        return $this->hasMany(AuctionBid::class, 'auction_id');
    }

    public function winner()
    {
        return $this->hasOne(AuctionWinner::class, 'auction_id');
    }
}
