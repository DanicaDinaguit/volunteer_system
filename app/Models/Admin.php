<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbladmin';
    protected $primaryKey = 'adminID'; // Specify your primary key column name
    public $incrementing = true; // Set to true if primary key is auto-incrementing
    protected $keyType = 'int'; // Use 'string' if the primary key is a UUID
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}