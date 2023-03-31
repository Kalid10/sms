<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Announcement::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;
        $body = $this->faker->paragraph;

        return [
            'title' => $title,
            'body' => $body,
            'author_id' => Admin::factory()->create()->id,
            'expires_on' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'target_group' => $this->faker->randomElement(['all', 'students', 'teachers', 'parents', 'admins']),
        ];
    }
}
