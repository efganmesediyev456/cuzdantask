<?php

namespace App\Import;

use App\Models\Transaction;
use App\Exceptions\InvalidCsvRowException;
use App\Exceptions\InvalidAmountException;
use Illuminate\Support\Facades\Log;

class CsvImporter
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function import(): array
    {
        if (!file_exists($this->path)) {
            throw new \InvalidArgumentException("File not found: {$this->path}");
        }

        $handle = fopen($this->path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Cannot open file: {$this->path}");
        }

        $header = null;
        $lineNo = 0;
        $imported = [];

        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;
            if ($row === [null] || count(array_filter($row, fn($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            if ($header === null) {
                $header = array_map('trim', $row);
                continue;
            }
            $header = [
                "date",
                "user_id",
                "user_type",
                "operation_type",
                "amount",
                "currency"
            ];


            if (count($row) !== count($header)) {
                throw new InvalidCsvRowException("Malformed CSV header/row at line {$lineNo}");
            }

            $data = array_combine($header, $row);


            try {
                $this->validateRow($data, $lineNo);
            } catch (\Exception $e) {
                throw new InvalidCsvRowException("Line {$lineNo}: " . $e->getMessage());
            }
            $opMap = [
                'deposit' => 'cash_in',
                'withdraw' => 'cash_out',
            ];
            $operationType = $opMap[$data['operation_type']] ?? $data['operation_type'];

            $transaction = Transaction::create([
                'date' => $data['date'],
                'user_type' => $data['user_type'],
                'operation_type' => $operationType,
                'amount' => (float) $data['amount'],
                'currency' => $data['currency'] ?? 'EUR',
            ]);

            $imported[] = $transaction;
        }

        fclose($handle);

        return $imported;
    }

    protected function validateRow(array $row, int $lineNo)
    {
        // date
        if (!isset($row['date']) || !strtotime($row['date'])) {
            throw new \InvalidArgumentException("Invalid date at line {$lineNo}");
        }
        // user_type
        if (!in_array($row['user_type'], ['private', 'business'])) {
            throw new \InvalidArgumentException("Invalid user_type at line {$lineNo}");
        }
        // operation_type
        if (!in_array($row['operation_type'], ['cash_in', 'cash_out', 'loan_repayment', 'deposit', 'withdraw'])) {
            throw new \InvalidArgumentException("Invalid operation_type at line {$lineNo}");
        }
        // amount
        if (!isset($row['amount']) || !is_numeric($row['amount'])) {
            throw new InvalidAmountException("Invalid amount at line {$lineNo}");
        }
        if ((float) $row['amount'] < 0) {
            throw new InvalidAmountException("Amount cannot be negative at line {$lineNo}");
        }
        // currency basic
        if (!isset($row['currency']) || strlen($row['currency']) !== 3) {
            $row['currency'] = 'EUR';
        }
    }
}
