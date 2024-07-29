<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'tblevent'; // Ensure the model uses the correct table

    protected $fillable = [
        'event_name', // adjust these to your table columns
        'event_time',
        'event_date',
        'description',
        'number_of_volunteers',
        'event_location',
        'category',
        'event_status',
        'created_at',
        'updated_at',
        'time_range',
        // Add other columns as needed
    ];
}
