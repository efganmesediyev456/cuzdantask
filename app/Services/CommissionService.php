<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\ValueObjects\Amount;
use App\Commission\CommissionFactory;
use Illuminate\Support\Collection;

class CommissionService
{
    /**
     * @param Transaction[]|Collection $transactions
     * @return array  keyed by transaction id => ['fee' => Amount, 'transaction' => Transaction]
     */
    public function calculateForAll($transactions): array
    {
        $results = [];
        $transactions = collect($transactions);

        foreach ($transactions as $tx) {
            $amountVo = new Amount((float)$tx->amount, $tx->currency);
            $commissionType = CommissionFactory::make($tx);
            $fee = $commissionType->calculate($amountVo, $tx);
            $results[$tx->id] = [
                'fee' => $fee,
                'transaction' => $tx,
            ];
        }

        return $results;
    }

    /**
     * Simple filtering helper
     */
    public function filter(array $criteria = [])
    {
        $query = Transaction::query();

        if (!empty($criteria['date_from'])) {
            $query->where('date', '>=', $criteria['date_from']);
        }
        if (!empty($criteria['date_to'])) {
            $query->where('date', '<=', $criteria['date_to']);
        }
        if (!empty($criteria['user_type'])) {
            $query->where('user_type', $criteria['user_type']);
        }
        if (!empty($criteria['operation_type'])) {
            $query->where('operation_type', $criteria['operation_type']);
        }
        return $query->get();
    }
}
