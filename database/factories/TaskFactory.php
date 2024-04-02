<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'       => $this->faker->sentence(3), 
            'description' => $this->faker->paragraph(2), 
            'due_date'    => $this->faker->dateTime(), 
            'state'       => $this->faker->randomElement(['PENDING', 'IN_PROGRESS', 'COMPLETE']),
            'user_id'     => $this->faker->randomElement(User::pluck('id'))
        ];
    }
}
