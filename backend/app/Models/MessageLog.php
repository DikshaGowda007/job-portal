<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    protected $table = 'message_logs';

    protected $fillable = [
        'application_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_delivered',
        'status',
        'is_deleted',
    ];
}
