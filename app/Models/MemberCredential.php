<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\MessageThread;

class MemberCredential extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tblmembercredentials';
    protected $primaryKey = 'memberCredentialsID'; // Specify your primary key column name
    public $incrementing = true; // Set to true if primary key is auto-incrementing
    protected $keyType = 'int'; // Use 'string' if the primary key is a UUID

    protected $fillable = [
        'positionID',
        'first_name',
        'middle_name',
        'last_name',
        'studentID',
        'email',
        'password',
        'aboutMe'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = true;

    /**
     * Get the notifications for the volunteer.
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
