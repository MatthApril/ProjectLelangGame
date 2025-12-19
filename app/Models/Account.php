<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

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
