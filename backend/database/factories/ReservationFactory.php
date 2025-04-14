<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 days', '+10 days');
        $end = (clone $start)->modify('+2 hours');

        return [
            'client_name' => $this->faker->name,
            'table_id' => $this->faker->numberBetween(1, 15),
            'phone_contact' => $this->faker->phoneNumber,
            'email_contact' => $this->faker->safeEmail,
            'guests' => $this->faker->numberBetween(1, 10),
            'reservation_start' => $start,
            'reservation_end' => $end,
        ];
    }
}
