<?php

namespace App\Commission;

use App\Models\Transaction;
use App\Exceptions\UnknownOperationTypeException;

class CommissionFactory
{
    public static function make(Transaction $transaction): CommissionTypeInterface
    {
        $op = $transaction->operation_type;
        $userType = $transaction->user_type;

        if ($op === 'cash_in') {
            return new CashInCommission();
        }

        if ($op === 'cash_out') {
            if ($userType === 'private') {
                return new CashOutPrivateCommission();
            }
            return new CashOutBusinessCommission();
        }

        if ($op === 'loan_repayment') {
            return new LoanRepaymentCommission();
        }

        throw new UnknownOperationTypeException("Unknown operation type: {$op}");
    }
}
