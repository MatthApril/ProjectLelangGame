<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;
    protected $table = 'games';
    protected $primaryKey = 'game_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'game_name',
        'game_img'
    ];

    public function gamesCategories()
    {
        return $this->hasMany(GameCategory::class, 'game_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'game_id');
    }

    protected static function booted()
    {
        static::restoring(function ($game) {
            $game->products()->onlyTrashed()->each(function ($product) {
                if (!$product->category?->deleted_at) {
                    $product->restore();
                }
            });

            $game->gamesCategories()->onlyTrashed()->each(function ($gc) {
                $gc->restore();
            });
        });
    }
}
