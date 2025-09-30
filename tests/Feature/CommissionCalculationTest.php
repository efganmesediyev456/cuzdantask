<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Commission\CashInCommission;
use App\Commission\CashOutPrivateCommission;
use App\Commission\CashOutBusinessCommission;
use App\Commission\LoanRepaymentCommission;
use App\Models\ValueObjects\Amount;
use App\Models\Transaction;

class CommissionCalculationTest extends TestCase
{
    public function test_cash_in_commission_limits()
    {
        $tx = new Transaction(['operation_type' => 'cash_in', 'user_type' => 'private']);
        $c = new CashInCommission();
        $fee = $c->calculate(new Amount(1000000, 'EUR'), $tx);
        $this->assertEquals(5.00, $fee->value);
    }

    public function test_cash_out_private_min()
    {
        $tx = new Transaction(['operation_type' => 'cash_out', 'user_type' => 'private']);
        $c = new CashOutPrivateCommission();
        $fee = $c->calculate(new Amount(10, 'EUR'), $tx);
        $this->assertEquals(0.5, $fee->value);
    }

    public function test_cash_out_business_flat()
    {
        $tx = new Transaction(['operation_type' => 'cash_out', 'user_type' => 'business']);
        $c = new CashOutBusinessCommission();
        $fee = $c->calculate(new Amount(1000, 'EUR'), $tx);
        $this->assertEquals(5.00, $fee->value);
    }

    public function test_loan_repayment()
    {
        $tx = new Transaction(['operation_type' => 'loan_repayment', 'user_type' => 'private']);
        $c = new LoanRepaymentCommission();
        $fee = $c->calculate(new Amount(500, 'EUR'), $tx);
        $this->assertEquals(11.00, $fee->value);
    }
}
