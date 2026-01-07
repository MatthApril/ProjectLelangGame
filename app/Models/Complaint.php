<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $primaryKey = 'complaint_id';
    public $timestamps = true;

    protected $fillable = [
        'order_item_id',
        'buyer_id',
        'seller_id',
        'description',
        'proof_img',
        'status',
        'decision',
        'is_auto_resolved',
        'resolved_at'
    ];
    protected $casts = [
        'resolved_at' => 'datetime',
        'is_auto_resolved' => 'boolean'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id')->withTrashed();
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id')->withTrashed();
    }

    public function response()
    {
        return $this->hasOne(ComplaintResponse::class, 'complaint_id', 'complaint_id');
    }

}
