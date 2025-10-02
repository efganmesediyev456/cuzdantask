<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Services\TransactionFilter;

class TransactionFilterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Transaction::factory()->create([
            'date' => '2025-01-01 00:00:00',
            'user_type' => 'private',
            'operation_type' => 'cash_in',
        ]);

        Transaction::factory()->create([
            'date' => '2025-02-01 00:00:00',
            'user_type' => 'business',
            'operation_type' => 'cash_out',
        ]);

        Transaction::factory()->create([
            'date' => '2025-03-01 00:00:00',
            'user_type' => 'private',
            'operation_type' => 'loan_repayment',
        ]);
    }

    public function test_filters_by_date_range()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter([
            'date_from' => '2025-02-01 00:00:00',
            'date_to'   => '2025-03-01 00:00:00',
        ]);

        $this->assertCount(2, $results);
        $this->assertEquals(['business', 'private'], $results->pluck('user_type')->toArray());
    }

    public function test_filters_by_user_type()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter(['user_type' => 'business']);

        $this->assertCount(1, $results);
        $this->assertEquals('business', $results->first()->user_type);
    }

    public function test_filters_by_operation_type()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter(['operation_type' => 'loan_repayment']);

        $this->assertCount(1, $results);
        $this->assertEquals('loan_repayment', $results->first()->operation_type);
    }

    public function test_custom_callback()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter([], function ($query) {
            return $query->where('user_type', 'private');
        });

        $this->assertCount(2, $results);
        $this->assertTrue($results->every(fn($tx) => $tx->user_type === 'private'));
    }


    public function test_returns_all_when_no_criteria()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter([]);

        $this->assertCount(3, $results);
    }

    public function test_filters_only_date_from()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter(['date_from' => '2025-02-01 00:00:00']);

        $this->assertCount(2, $results);
        $this->assertEquals(['business', 'private'], $results->pluck('user_type')->toArray());
    }

     public function test_filters_only_date_to()
    {
        $filter = new TransactionFilter();

        $results = $filter->filter(['date_to' => '2025-02-01 00:00:00']);

        $this->assertCount(2, $results);
        $this->assertEquals(['private', 'business'], $results->pluck('user_type')->toArray());
    }
}
