<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryAttendance extends Model
{
    use HasFactory;

    protected $table = 'tblbeneficiaries_attendance'; // Specify the table name

    protected $fillable = [
        'eventID',
        'beneficiaryID',
        'date_attended'
    ];

    // Define relationships or additional methods here if needed
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiaryID', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventID', 'id');
    }
}
