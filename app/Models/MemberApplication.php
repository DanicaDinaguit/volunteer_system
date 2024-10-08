<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberApplication extends Model
{
    use HasFactory;

    protected $table = 'tblmemberapplication';
    protected $primaryKey = 'memberApplicationID';
    public $incrementing = true; // Set to true if primary key is auto-incrementing
    protected $keyType = 'int'; // Use 'string' if the primary key is a UUID

    protected $fillable = [
        'name',
        'phone_number',
        'email_address',
        'age',
        'address',
        'religion',
        'gender',
        'citizenship',
        'civil_status',
        'college',
        'course',
        'year_level',
        'schoolID',
        'high_school',
        'elementary',
        'reasons_for_joining',
        'status',
    ];
    public $timestamps = true;
}
