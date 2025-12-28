<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionWinner extends Model
{
    protected $table = 'auction_winners';
    protected $primaryKey = 'winner_id';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'auction_id',
        'user_id',
        'winning_price',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
