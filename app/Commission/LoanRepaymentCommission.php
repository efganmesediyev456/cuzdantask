<?php

namespace App\Commission;

use App\Models\ValueObjects\Amount;
use App\Models\Transaction;

class LoanRepaymentCommission implements CommissionTypeInterface
{
    public function calculate(Amount $amount, Transaction $transaction): Amount
    {
        $fee = $amount->value * 0.02 + 1.0; 
        return (new Amount($fee, $amount->currency))->rounded(2);
    }
}
