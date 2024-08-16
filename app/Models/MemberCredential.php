<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MemberCredential extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tblmembercredentials';
    protected $primaryKey = 'memberApplicationID'; // Specify your primary key column name
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
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = true;
}
