<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $table = 'products_comments';
    protected $primaryKey = 'comment_id';
    public $timestamps = true;
    public $incrementing = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'content',
        'rating'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
