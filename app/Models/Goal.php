<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Investment;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'icon',
        'target_amount',
        'target_date',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
