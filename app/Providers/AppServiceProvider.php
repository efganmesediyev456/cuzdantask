<?php

namespace App\Providers;


use App\Factories\CommissionFactory;
use App\Factories\CommissionFactoryInterface;
use App\Import\CsvImporter;
use App\Import\CsvImporterInterface;
use App\Import\CsvRowValidator;
use App\Repositories\TransactionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CommissionFactoryInterface::class, CommissionFactory::class);
        $this->app->bind(CsvImporterInterface::class, function($app) {
            return new CsvImporter('', $app->make(CsvRowValidator::class), $app->make(TransactionRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
