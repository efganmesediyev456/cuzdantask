<?php

namespace App\Commission;

use App\Models\ValueObjects\Amount;
use App\Models\Transaction;

class CashInCommission implements CommissionTypeInterface
{
    public function calculate(Amount $amount, Transaction $transaction): Amount
    {
        $fee = $amount->value * 0.0003; 
        if ($fee > 5.0) $fee = 5.0;
        return (new Amount($fee, $amount->currency))->rounded(2);
    }
}
