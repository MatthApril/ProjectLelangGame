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
        'shop_rating'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_id');
    }

    public function appeals()
    {
        return $this->hasMany(ShopAppeal::class, 'appeal_shop_id');
    }
}
