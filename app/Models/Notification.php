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
        'message',
        'type'
    ];

    public function notificationRecipients()
    {
        return $this->hasMany(NotificationRecipient::class, 'notification_id');
    }
}
