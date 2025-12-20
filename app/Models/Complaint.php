<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $primaryKey = 'complaint_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'content',
        'proof_img',
        'is_served'
    ];

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_id');
    }
}
