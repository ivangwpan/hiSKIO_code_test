<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table = 'balances';

    protected $fillable = ['user_id', 'current_balance', 'passed_balance'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
