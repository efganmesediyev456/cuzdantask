<?php

namespace App\Services;

use App\Factories\CommissionFactoryInterface;
use App\Models\Transaction;
use App\Models\ValueObjects\Amount;

class CommissionService
{

    private CommissionFactoryInterface $factory;

    public function __construct(CommissionFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function calculateForAll($transactions): array
    {
        $results = [];
        $transactions = collect($transactions);
        foreach ($transactions as $tx) {
            try {
                $amountVo = new Amount((float) $tx->amount, $tx->currency);
                $commissionType = $this->factory->make($tx);
                $fee = $commissionType->calculate($amountVo, $tx);
                $results[$tx->id] = [
                    'fee' => $fee,
                    'transaction' => $tx,
                ];
            } catch (\Exception $e) {
                \Log::error("Commission calculation failed for TX ID {$tx->id}: " . $e->getMessage());
                continue;
            }
        }
        return $results;
    }
}
