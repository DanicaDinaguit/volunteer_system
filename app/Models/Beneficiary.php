<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;
    protected $table = 'tblbeneficiary'; // Specify the table name

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'purok',
        'birthdate',
        'created_at',
    ];

    public function attendances()
{
    return $this->hasMany(BeneficiaryAttendance::class, 'beneficiaryID', 'id');
}
}
