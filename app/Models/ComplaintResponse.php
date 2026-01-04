<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    protected $table = 'complaint_responses';
    protected $primaryKey = 'response_id';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'complaint_id',
        'message',
        'attachment'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'complaint_id');
    }
}
