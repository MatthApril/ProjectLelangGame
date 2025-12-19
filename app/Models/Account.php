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

}
