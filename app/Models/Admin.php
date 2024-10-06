<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MessageThread;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbladmin';
    protected $primaryKey = 'adminID'; // Specify your primary key column name
    public $incrementing = true; // Set to true if primary key is auto-incrementing
    protected $keyType = 'int'; // Use 'string' if the primary key is a UUID
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the notifications for the admin.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user');
    }

    public function messageThreads()
    {
        return $this->morphToMany(MessageThread::class, 'participant', 'message_thread_participants', 'participant_id', 'thread_id')
        ->withPivot('participant_type')
        ->withTimestamps();
    }
}