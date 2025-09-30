<?php

namespace App\Commission;

use App\Models\ValueObjects\Amount;
use App\Models\Transaction;

class CashOutBusinessCommission implements CommissionTypeInterface
{
    public function calculate(Amount $amount, Transaction $transaction): Amount
    {
        $fee = $amount->value * 0.005; 
        return (new Amount($fee, $amount->currency))->rounded(2);
    }
}
