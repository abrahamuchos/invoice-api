<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()
            ->count(25)
            ->hasInvoices(9)
            ->create();

        Customer::factory()
            ->count(100)
            ->hasInvoices(3)
            ->create();

        Customer::factory()
            ->count(12)
            ->hasInvoices(7)
            ->create();

        Customer::factory()
            ->count(15)
            ->hasInvoices(11)
            ->create();

        Customer::factory()
            ->count(20)
            ->hasInvoices(13)
            ->create();

        Customer::factory()
            ->count(10)
            ->hasInvoices(5)
            ->create();

    }
}
