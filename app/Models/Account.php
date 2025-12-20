<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes;
    use Notifiable;

    protected $table = "accounts";                //default -- nama class + s = Kotas
    protected $primaryKey = "account_id";      //default -- id
    public $incrementing = true;             // PK AI ?
    public $timestamps = true;               // SOFT DELETES:created_at? updated_at? deleted_at?

    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
    ];

    public function carts()
    {
        return $this->hasOne(Cart::class, 'account_id');
    }

    public function shops()
    {
        return $this->hasOne(Shop::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'account_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'customer_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notificationRecipients()
    {
        return $this->hasMany(NotificationRecipient::class, 'account_id');
    }
}
