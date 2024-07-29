<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MemberCredential extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tblmembercredentials';

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
