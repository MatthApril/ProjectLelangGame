<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationTemplate extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'notification_templates';
    protected $primaryKey = 'notif_temp_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'code_tag',
        'title',
        'subject',
        'body',
        'trigger_type',
        'category'
    ];

    /**
     * Relationship: A template can have many historical logs of being sent.
     */
    public function logs()
    {
        return $this->hasMany(NotificationLog::class, 'template_id');
    }

    /**
     * Relationship: A template generates many actual notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'template_id');
    }

    /**
     * Helper: Check if this template allows manual broadcasting.
     * Usage: if ($template->isBroadcast()) { showButton(); }
     */
    public function isBroadcast()
    {
        return $this->type === 'broadcast';
    }

    public function isTransactional()
    {
        return $this->type === 'transactional';
    }
}
