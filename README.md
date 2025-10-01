# Commission Calculator (Laravel)

## Overview
Bu layihə, maliyyə əməliyyatları üçün **komissiya kalkulyatoru** yaratmaq üçün hazırlanmışdır. 
Əməliyyat növləri:
- `cash_in`
- `cash_out` (private/business)
- `loan_repayment`

Sistem **extensible**, **testable** və **OOP prinsiplərinə uyğun** qurulub.

---

## Requirements
- PHP 8.1+
- Composer
- Laravel 10+
- MySQL/PostgreSQL və ya digər Laravel-supported DB

---

## Installation

1. Repository-ni clone et və Laravel layihəsini qur:
```bash
git clone <repo-url> commission-calculator
cd commission-calculator
composer install
cp .env.example .env
php artisan key:generate
```

2. CSV Import
Nümunə CSV

examples/transactions_sample.csv
```bash
date,user_type,operation_type,amount,currency
2025-09-01,private,cash_in,2000.00,EUR
2025-09-02,business,cash_out,1000.00,EUR
2025-09-03,private,cash_out,50.00,EUR
2025-09-04,private,loan_repayment,500.00,EUR
```
Import əmri
```bash
php artisan transactions:import examples/transactions_sample.csv
```

Düzgün sətirlər transactions cədvəlinə əlavə olunur.

Düzgün olmayan sətirlər üçün descriptive exceptions atılır.

2. Komissiya Hesablama
Bütün əməliyyatlar üçün
php artisan commissions:calculate

Filtrləmiş əməliyyatlar
```bash
php artisan commissions:calculate --date_from=2025-09-01 --date_to=2025-09-30 --user_type=private --operation_type=cash_out
```
Output nümunəsi:
```bash
Tx 1 | 2025-09-01 | cash_in | amount=2000 EUR => fee=0.60 EUR
Tx 2 | 2025-09-02 | cash_out | amount=1000 EUR => fee=5.00 EUR
...

Total fees: 16.10 EUR
```
Commission Rules
Operation Type	User Type	Fee Calculation	Min/Max
cash_in	all	0.03% of amount	max 5
cash_out	private	0.3% of amount	min 0.5
cash_out	business	0.5% of amount	-
loan_repayment	all	2% + 1 unit	-
Filtering

date_from / date_to

user_type → private / business

operation_type → cash_in / cash_out / loan_repayment

Testing

Unit testlər üçün PHPUnit istifadə olunur.

# run all tests
```bash
composer test
```
# or
```bash
vendor/bin/phpunit
```