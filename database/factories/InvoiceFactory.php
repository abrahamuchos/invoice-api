<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['B', 'P', 'V']);

        return [
            'customer_id' => Customer::factory(),
            'invoice_number' => DB::select("select nextval('invoice_number_seq')")[0]->nextval,
            'uuid' => $this->faker->uuid(),
            'amount' => (float) rand(100, 250) / 10,
            'status' => $status,
            'billed_dated' => $this->faker->dateTimeThisDecade,
            'paid_dated' => $status === 'P' ? $this->faker->dateTimeThisDecade() : NULL,
        ];
    }
}
