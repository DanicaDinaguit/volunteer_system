<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreadParticipant extends Model
{
    use HasFactory;
    // Specify the table associated with the model
    protected $table = 'message_thread_participants';

    // Specify the primary key for the table
    protected $primaryKey = 'id';

    // Since 'id' is an auto-incrementing primary key, you don't need to set 'incrementing' to false
    public $incrementing = true;

    // Specify the data type of the primary key (bigint)
    protected $keyType = 'int';

    // If you're not using timestamp fields in your table, set this to false
    public $timestamps = true;

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'thread_id',
        'participant_id',
        'participant_type',
    ];

    // Optionally, define relationships if needed (example: belongs to MessageThread)
    public function messageThread()
    {
        return $this->belongsTo(MessageThread::class, 'thread_id');
    }
    
    
}
