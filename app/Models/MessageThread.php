<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_group_chat'];
    protected $primaryKey = 'id';

    // Since 'id' is an auto-incrementing primary key, you don't need to set 'incrementing' to false
    public $incrementing = true;

    // Specify the data type of the primary key (bigint)
    protected $keyType = 'int';
    public function adminParticipants()
    {
        return $this->morphToMany(Admin::class, 'participant', 'message_thread_participants', 'thread_id', 'participant_id')
                    ->withPivot('participant_type') // Ensure 'participant_type' is used from the pivot table
                    ->withTimestamps();
    }
    
    public function memberParticipants()
    {
        return $this->morphToMany(MemberCredential::class, 'participant', 'message_thread_participants', 'thread_id', 'participant_id')
                    ->withPivot('participant_type') // Ensure 'participant_type' is used from the pivot table
                    ->withTimestamps();
    }
    

    public function participants()
    {
        return $this->morphToMany(Admin::class, 'participant', 'message_thread_participants', 'thread_id', 'participant_id')
            ->withPivot('participant_type')
            ->withTimestamps()
            ->where(function ($query) {
                $query->where('participant_type', 'admin')
                    ->orWhere('participant_type', 'volunteer');
            });
    }
    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'thread_id', 'id')->latestOfMany();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'thread_id');
    }
}
