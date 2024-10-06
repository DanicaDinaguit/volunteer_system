<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id', 
        'message_content', 
        'sender_type', 
        'receiver_type', 
        'thread_id',
        'read_at'
    ];

    public function sender()
    {
        return $this->morphTo(null, 'sender_type', 'sender_id');
    }

    public function receiver()
    {
        return $this->morphTo(null, 'receiver_type', 'receiver_id');
    }

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'thread_id');
    }
    
}
