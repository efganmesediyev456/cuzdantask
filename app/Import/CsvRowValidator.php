<?php

namespace App\Import;

use App\Exceptions\InvalidAmountException;

class CsvRowValidator
{
    public function validate(array $row, int $lineNo)
    {
        if (!isset($row['date']) || !strtotime($row['date'])) {
            throw new \InvalidArgumentException("Invalid date at line {$lineNo}");
        }

        if (!in_array($row['user_type'], ['private', 'business'])) {
            throw new \InvalidArgumentException("Invalid user_type at line {$lineNo}");
        }

        if (!in_array($row['operation_type'], ['cash_in', 'cash_out', 'loan_repayment', 'deposit', 'withdraw'])) {
            throw new \InvalidArgumentException("Invalid operation_type at line {$lineNo}");
        }

        if (!isset($row['amount']) || !is_numeric($row['amount']) || (float)$row['amount'] < 0) {
            throw new InvalidAmountException("Invalid amount at line {$lineNo}");
        }

        if (!isset($row['currency']) || strlen($row['currency']) !== 3) {
            $row['currency'] = 'EUR';
        }
        return $row;
    }
}
