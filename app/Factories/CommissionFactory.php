<?php

namespace App\Factories;

use App\Commission\CashInCommission;
use App\Commission\CashOutBusinessCommission;
use App\Commission\CashOutPrivateCommission;
use App\Commission\CommissionTypeInterface;
use App\Commission\LoanRepaymentCommission;
use App\Exceptions\UnknownOperationTypeException;
use App\Models\Transaction;


class CommissionFactory implements CommissionFactoryInterface
{
    private static array $map = [
        'cash_in' => CashInCommission::class,
        'cash_out_private' => CashOutPrivateCommission::class,
        'cash_out_business' => CashOutBusinessCommission::class,
        'loan_repayment' => LoanRepaymentCommission::class,
    ];
    public static function make(Transaction $transaction): CommissionTypeInterface
    {
        $key = $transaction->operation_type;
        if ($key === 'cash_out') {
            $key .= '_' . $transaction->user_type; 
        }

        if (!isset(self::$map[$key])) {
            throw new UnknownOperationTypeException("Unknown operation type: {$key}");
        }

        return new self::$map[$key]();
    }
}
