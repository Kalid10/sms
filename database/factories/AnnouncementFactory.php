<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Announcement>
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
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'author_id' => Admin::factory()->create()->id,
            'expires_on' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'target_group' => $this->faker->randomElements(['all', 'students', 'teachers', 'parents', 'admins']),
            'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
        ];
    }
}
