<?php 
namespace App\Repositories;

use App\Models\Transaction;

interface TransactionRepositoryInterface {
    public function create(array $data): Transaction;
}
