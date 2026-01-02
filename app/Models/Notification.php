<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    public $timestamps = true;
    const UPDATED_AT = null;
    public $incrementing = true;

    protected $fillable = [
        'title',
        'body',
        'category'
    ];

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    public function recipients()
    {
        return $this->hasMany(NotificationRecipient::class, 'notification_id');
    }
}
