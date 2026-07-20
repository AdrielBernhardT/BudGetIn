<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'bio',
        'country',
        'city',
        'postal_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
