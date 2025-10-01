<?php

namespace App\Console\Commands;

use App\Services\TransactionFilter;
use Illuminate\Console\Command;
use App\Services\CommissionService;
use App\Models\Transaction;

class CalculateCommissions extends Command
{
    protected $signature = 'commissions:calculate {--date_from=} {--date_to=} {--user_type=} {--operation_type=}';
    protected $description = 'Calculate commissions for transactions';

    public function handle(CommissionService $service, TransactionFilter $transactionFilter)
    {
        $criteria = [
            'date_from' => $this->option('date_from'),
            'date_to' => $this->option('date_to'),
            'user_type' => $this->option('user_type'),
            'operation_type' => $this->option('operation_type'),
        ];
        $transactions = $transactionFilter->filter($criteria);
        $results = $service->calculateForAll($transactions);
        foreach ($results as $txId => $info) {
            $tx = $info['transaction'];
            $fee = $info['fee'];
            $this->line("Tx {$tx->id} | {$tx->date->toDateString()} | {$tx->operation_type} | amount={$tx->amount} {$tx->currency} => fee={$fee->value} {$fee->currency}");
        }
        $sum = array_reduce($results, fn($carry, $it) => $carry + $it['fee']->value, 0);
        $this->info("Total fees: " . number_format($sum, 2));
    }
}
