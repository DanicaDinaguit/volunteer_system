<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'tblparticipants';
    protected $primaryKey = 'participantsID';

    protected $fillable = [
        'memberCredentialsID',
        'eventID'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventID');
    }

    public function volunteer()
    {
        return $this->belongsTo(MemberCredential::class, 'memberCredentialsID');
    }
}
