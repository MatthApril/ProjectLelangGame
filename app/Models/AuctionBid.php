<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model
{
    protected $table = 'auction_bids';
    protected $primaryKey = 'bid_id';
    public $timestamps = true;
    const UPDATED_AT = null;
    protected $fillable = [
        'auction_id',
        'user_id',
        'bid_price',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id', 'auction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
