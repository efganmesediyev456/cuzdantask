<?php

namespace Tests\Feature;

use App\Exceptions\InvalidCsvRowException;
use App\Import\CsvImporterInterface;
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
        $importer = app(CsvImporterInterface::class);
        $importer->setPath($path);
        $items = $importer->import();
        $this->assertCount(13, $items);
        $this->assertDatabaseHas('transactions', ['operation_type' => 'cash_in', 'amount' => 200]);
    }

    public function test_invalid_row_throws()
    {
        $path = base_path('examples/invalid_throw_transactions_sample.csv'); 
        $importer = app(CsvImporterInterface::class);
        $importer->setPath($path);
        $this->expectException(InvalidCsvRowException::class);
        $importer->import();
    }
}
