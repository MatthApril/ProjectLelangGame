<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $table = 'notification_logs';
    protected $primaryKey = 'notif_log_id';
    public $timestamps = false;

    protected $fillable = [
        'notif_temp_id',
        'admin_id',
        'target_audience',
        'recipients_count',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Relationship: Each log entry belongs to a notification template.
     */
    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    /**
     * Relationship: The Admin (User) who clicked send.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
