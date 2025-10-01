<?php

namespace App\Console\Commands;

use App\Import\CsvImporterInterface;
use Illuminate\Console\Command;
use App\Import\CsvImporter;

class ImportTransactions extends Command
{
    protected $signature = 'transactions:import {file}';
    protected $description = 'Import transactions from CSV';

    public function handle(CsvImporterInterface $csvImporter)
    {
        $file = $this->argument('file');
        $csvImporter->setPath($file);

        try {
            $imported = $csvImporter->import();
            $this->info('Imported ' . count($imported) . ' transactions.');
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
