<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refund';

    protected $fillable = [
        'order_item_id',
        'reason',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id')->withTrashed();
    }
}
