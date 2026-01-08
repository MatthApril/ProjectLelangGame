<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTickets extends Model
{
    protected $table = 'support_tickets';
    protected $primaryKey = 'ticket_id';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'subject',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'ticket_id', 'ticket_id');
    }
}
