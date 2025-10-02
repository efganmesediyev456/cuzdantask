<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'user_type' => $this->faker->randomElement(['private', 'business']),
            'operation_type' => $this->faker->randomElement(['cash_in', 'cash_out', 'loan_repayment']),
            'amount' => $this->faker->randomFloat(2, 1, 10000),
            'currency' => $this->faker->randomElement(['EUR', 'USD']),
        ];
    }
}
