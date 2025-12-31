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
        'category_name'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function gamesCategories()
    {
        return $this->hasMany(GameCategory::class, 'category_id');
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            $category->products()->whereNull('deleted_at')->each(function ($product) {
                $product->delete();
            });

            $category->gamesCategories()->whereNull('deleted_at')->each(function ($gc) {
                $gc->delete();
            });
        });

        static::restoring(function ($category) {

            $category->products()->onlyTrashed()->each(function ($product) {
                if (!$product->game?->deleted_at) {
                    $product->restore();
                }
            });

            $category->gamesCategories()->onlyTrashed()->each(function ($gc) {
                $gc->restore();
            });
        });
    }
}
