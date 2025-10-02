<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'date','user_type','operation_type','amount','currency'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:4',
    ];
}
