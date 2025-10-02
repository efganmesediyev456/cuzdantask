<?php

use App\Models\Transaction;
use App\Services\CommissionService;
use App\Services\TransactionFilter;
use Illuminate\Support\Facades\Route;

Route::get('/', function(CommissionService $service) {
    $data = $service->calculateForAll(Transaction::all());
    return $data;
});

// Route::get('/', function(CommissionService $service, TransactionFilter $filter) {
//     $transactions = $filter->filter([
//         'user_type' => 'business',
//         'operation_type' => 'cash_out',
//         'date_from' => '2016-01-01',
//         'date_to' => '2016-12-31',
//     ]);
//     $data = $service->calculateForAll($transactions);
//     return $data;
// });