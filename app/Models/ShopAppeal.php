<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopAppeal extends Model
{
    protected $table = 'shops_appeals';
    protected $primaryKey = 'appeal_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'appeal_shop_id',
        'justification',
        'is_approved'
    ];


    public function shop()
    {
        return $this->belongsTo(Shop::class, 'appeal_shop_id');
    }
}
