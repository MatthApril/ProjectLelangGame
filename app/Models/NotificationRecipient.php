<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationRecipient extends Model
{
    use SoftDeletes;

    protected $table = 'notification_recipients';
    protected $primaryKey = 'notif_recip_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'notification_id',
        'user_id',
        'is_read',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
