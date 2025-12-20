<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRecipient extends Model
{
    protected $table = 'notification_recipients';
    protected $primaryKey = 'notif_recip_id';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'notification_id',
        'account_id'
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
