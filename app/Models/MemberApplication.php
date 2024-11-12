<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberApplication extends Model
{
    use HasFactory;

    protected $table = 'tblmemberapplication';
    protected $primaryKey = 'memberApplicationID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'email_address',
        'birthdate',
        'address_street',
        'address_city',
        'address_state',
        'address_zipcode',
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
