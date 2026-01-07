<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSettings extends Model
{
    protected $table = 'admin_settings';
    protected $primaryKey = 'admin_setting_id';
    public $timestamps = true;

    protected $fillable = [
        'platform_fee_percentage',
    ];

    protected $casts = [
        'platform_fee_percentage' => 'float',
    ];
}
