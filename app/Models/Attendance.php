<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'tblattendance';

    // Define the primary key for the model
    protected $primaryKey = 'id';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'participantsID',
        'studentID',
        'full_name',
        'course',
        'status',
        'time_in',
        'time_out',
    ];

    // Disable auto-increment if the primary key is not auto-incrementing (optional)
    public $incrementing = true;

    // If your primary key is not an integer, specify the key type (optional)
    protected $keyType = 'int';

    // Define the relationship with the Participant model
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participantsID', 'participantsID');
    }
    // Relationship to access the Event through Participant
    public function event()
    {
        return $this->participant->event();
    }
    public function volunteer()
    {
        return $this->belongsTo(MemberCredential::class, 'memberCredentialsID');
    }
}
