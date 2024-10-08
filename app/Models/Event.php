<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'tblevent'; // Ensure the model uses the correct table
    protected $primaryKey = 'id'; // Specify your primary key column name
    public $incrementing = true; // Set to true if primary key is auto-incrementing
    protected $keyType = 'int';
    protected $fillable = [
        'title', // adjust these to your table columns
        'start',
        'end',
        'event_date',
        'description',
        'number_of_volunteers',
        'volunteers_joined',
        'event_location',
        'category',
        'event_status',
        'created_at',
        'updated_at',
        'time_range',
        // Add other columns as needed
    ];
}
