<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionFilter
{
    public function filter(array $criteria = [], \Closure $custom = null)
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

        if ($custom) {
            $query = $custom($query);
        }
        return $query->get();
    }
}
