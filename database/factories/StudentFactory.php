<?php

namespace Database\Factories;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create([
                'type' => 'student',
                'username' => 'GSN/'.$this->faker->unique()->numberBetween(1000, 9999).'/2021',
            ])->id,
            'guardian_id' => Guardian::inRandomOrder()->first() ?? Guardian::factory(),
        ];
    }
}
