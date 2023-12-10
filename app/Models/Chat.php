<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{

    protected $fillable=[
        'sender_id',
        'sender_name',
        'message',
        'post_table',
        'reciver_id',
        'post_id',
        'match_id',
    ];

    use HasFactory;
}
