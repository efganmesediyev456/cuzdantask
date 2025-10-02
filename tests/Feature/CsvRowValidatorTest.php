<?php

namespace Tests\Unit;

use App\Import\CsvRowValidator;
use App\Exceptions\InvalidAmountException;
use PHPUnit\Framework\TestCase;

class CsvRowValidatorTest extends TestCase
{
    public function test_invalid_date_throws()
    {
        $validator = new CsvRowValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->validate(['date' => 'not-a-date', 'user_type'=>'private','operation_type'=>'cash_in','amount'=>100,'currency'=>'EUR'], 1);
    }

    public function test_invalid_user_type_throws()
    {
        $validator = new CsvRowValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->validate(['date'=>'2025-01-01','user_type'=>'alien','operation_type'=>'cash_in','amount'=>100,'currency'=>'EUR'], 2);
    }

    public function test_invalid_operation_type_throws()
    {
        $validator = new CsvRowValidator();
        $this->expectException(\InvalidArgumentException::class);
        $validator->validate(['date'=>'2025-01-01','user_type'=>'private','operation_type'=>'hack','amount'=>100,'currency'=>'EUR'], 3);
    }

    public function test_invalid_amount_throws()
    {
        $validator = new CsvRowValidator();
        $this->expectException(InvalidAmountException::class);
        $validator->validate(['date'=>'2025-01-01','user_type'=>'private','operation_type'=>'cash_in','amount'=>'abc','currency'=>'EUR'], 4);
    }

    public function test_missing_currency_defaults_to_eur()
    {
        $validator = new CsvRowValidator();
        $row = $validator->validate(['date'=>'2025-01-01','user_type'=>'private','operation_type'=>'cash_in','amount'=>100], 5);
        $this->assertEquals('EUR', $row['currency']);
    }
}
