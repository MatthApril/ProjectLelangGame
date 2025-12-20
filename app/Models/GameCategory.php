<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameCategory extends Model
{
    use SoftDeletes;
    protected $table = 'games_categories';
    protected $primaryKey = 'game_category_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'game_id',
        'category_id'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
