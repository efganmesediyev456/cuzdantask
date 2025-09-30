<?php

namespace App\Commission;

use App\Models\ValueObjects\Amount;
use App\Models\Transaction;

class CashOutPrivateCommission implements CommissionTypeInterface
{
    public function calculate(Amount $amount, Transaction $transaction): Amount
    {
        $fee = $amount->value * 0.003; 
        if ($fee < 0.5) $fee = 0.5;
        return (new Amount($fee, $amount->currency))->rounded(2);
    }
}
