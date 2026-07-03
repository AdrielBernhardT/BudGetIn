<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'balance',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }

    public function investmentRecords()
    {
        return $this->hasMany(RecordInvestment::class);
    }
}
