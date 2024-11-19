<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'tblpartner';
    
    protected $fillable = [
        'name', 
        'contact_person', 
        'email', 
        'phone', 
        'address', 
        'description',
        'date_partnered'
    ];
}
