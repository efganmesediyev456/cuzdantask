<?php

namespace App\Import;

use App\Models\Transaction;

interface CsvImporterInterface
{
    /**
     * Import CSV and return array of Transaction objects
     *
     * @return Transaction[]
     */
    public function import(): array;

    public function setPath(string $path):self;
}
