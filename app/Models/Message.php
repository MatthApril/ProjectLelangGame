<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'message_id';
    public $timestamps = true;
    public $incrementing = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTickets::class, 'ticket_id', 'ticket_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
