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
        'first_name',
        'middle_name',
        'last_name',
        'purok',
        'date_attended'
    ];

    // Define relationships or additional methods here if needed
}
