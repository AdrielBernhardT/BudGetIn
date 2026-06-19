<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Investment;

class RecordInvestment extends Model
{
    protected $fillable = [
        'investment_id',
        'goal_id',
        'account_id',
        'date',
        'transaction_amount',
        'description',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }
}
