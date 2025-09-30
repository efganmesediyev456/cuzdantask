<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Import\CsvImporter;
use App\Models\Transaction;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_csv_imports()
    {
        $path = base_path('examples/transactions_sample.csv');
        $importer = new CsvImporter($path);
        $items = $importer->import();
        $this->assertCount(12, $items);
        $this->assertDatabaseHas('transactions', ['operation_type' => 'cash_in', 'amount' => 200]);
    }

    public function test_invalid_row_throws()
    {
        $path = base_path('examples/invalid_throw_transactions_sample.csv'); 
        $this->expectException(\App\Exceptions\InvalidCsvRowException::class);
        (new CsvImporter($path))->import();
    }
}
