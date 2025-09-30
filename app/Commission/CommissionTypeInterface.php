<?php

namespace App\Commission;

use App\Models\ValueObjects\Amount;
use App\Models\Transaction;

interface CommissionTypeInterface
{
    public function calculate(Amount $amount, Transaction $transaction): Amount;
}
