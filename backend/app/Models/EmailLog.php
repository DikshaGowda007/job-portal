<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_logs';

    protected $fillable = [
        'email_template_id',
        'email_vendor_account_id',
        'to_email_id',
        'email_subject',
        'email_body',
        'user_id',
        'onboard_user_id',
        'om_row_id',
        'is_sent_received',
        'status',
        'is_deleted',
    ];

    protected $casts = [
        'is_sent_received' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public $timestamps = false;
}
