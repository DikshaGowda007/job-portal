<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationMessage extends Model
{
    protected $table = 'application_messages';

    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'sender_id',
        'message',
        'created_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
