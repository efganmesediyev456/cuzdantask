<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Import\CsvImporter;

class ImportTransactions extends Command
{
    protected $signature = 'transactions:import {file}';
    protected $description = 'Import transactions from CSV';

    public function handle()
    {
        $file = $this->argument('file');
        $importer = new CsvImporter($file);

        try {
            $imported = $importer->import();
            $this->info('Imported ' . count($imported) . ' transactions.');
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
