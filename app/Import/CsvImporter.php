<?php

namespace App\Import;

use App\Models\Transaction;
use App\Exceptions\InvalidCsvRowException;
use App\Exceptions\InvalidAmountException;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CsvImporter implements CsvImporterInterface
{
    private string $path;
    private CsvRowValidator $validator;
    private TransactionRepositoryInterface $repository;


    public function __construct(string $path, CsvRowValidator $validator,TransactionRepositoryInterface $repository)
    {
        $this->path = $path;
        $this->validator = $validator;
        $this->repository = $repository;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
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

        $header = ["date", "user_id", "user_type", "operation_type", "amount", "currency"];

        $lineNo = 0;
        $imported = [];

        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;
            if ($row === [null] || count(array_filter($row, fn($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            // if csv file has header titles
            // if ($header === null) {
            //     $header = array_map('trim', $row);
            //     continue;
            // }

            if (count($row) !== count($header)) {
                throw new InvalidCsvRowException("Malformed CSV header/row at line {$lineNo}");
            }
            $data = array_combine($header, $row);

            try {
                $data = $this->validator->validate($data, $lineNo);
            } catch (\Exception $e) {
                throw new InvalidCsvRowException("Line {$lineNo}: " . $e->getMessage());
            }
            $opMap = ['deposit' => 'cash_in', 'withdraw' => 'cash_out'];
            $operationType = $opMap[$data['operation_type']] ?? $data['operation_type'];
            $transaction = $this->repository->create([
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
}
