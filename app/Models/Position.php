<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'tblposition'; // Ensure the model uses the correct table

    protected $primaryKey = 'positionID'; // Explicitly define the primary key

    public $incrementing = true; // Ensure auto-incrementing if it's a non-integer primary key
    protected $keyType = 'int'; // Set the primary key type

    protected $fillable = [
        'position_name',
        'created_at',
        'updated_at',
        // Add other columns as needed
    ];
}
