<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'user_type', // Add this field to differentiate between Admin and MemberCredential
        'type',
        'title',
        'body',
        'url',
        'is_read',
    ];

    /**
     * Get the owning user model (either Admin or MemberCredential).
     */
    public function user()
    {
        return $this->morphTo();
    }
}
