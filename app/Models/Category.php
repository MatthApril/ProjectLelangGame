<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'category_name',
        'category_img'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function gamesCategories()
    {
        return $this->hasMany(GameCategory::class, 'category_id');
    }
}
