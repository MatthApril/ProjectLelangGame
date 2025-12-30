<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'user_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'owner_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'customer_id', 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'user_id');
    }

    public function notificationRecipients()
    {
        return $this->hasMany(NotificationRecipient::class, 'user_id', 'user_id');
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'seller_id', 'user_id');
    }

    public function auctionBids()
    {
        return $this->hasMany(AuctionBid::class, 'user_id', 'user_id');
    }

    public function auctionWins()
    {
        return $this->hasMany(AuctionWinner::class, 'user_id', 'user_id');
    }
    
    public function comments(){
        return $this->hasMany(ProductComment::class,'user_id','user_id');
    }

    

}
