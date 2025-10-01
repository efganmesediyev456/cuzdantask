<?php

namespace App\Factories;

use App\Commission\CommissionTypeInterface;
use App\Models\Transaction;

interface CommissionFactoryInterface
{
    public static function make(Transaction $transaction): CommissionTypeInterface;
}
