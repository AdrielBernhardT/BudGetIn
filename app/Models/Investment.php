<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RecordInvestment;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'goal_id',
        'name',
        'allocation_percent',
        'planned_amount',
    ];
    
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function records()
    {
        return $this->hasMany(RecordInvestment::class);
    }
}
