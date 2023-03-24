<?php

namespace Database\Factories;

namespace Database\Factories;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SchoolYearFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SchoolYear::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startYear = $this->faker->unique()->numberBetween(-3, 0);
        $startDate = Carbon::createFromDate(null, 9, 1)->addYears($startYear);
        $endDate = $startYear == 0 ? null : $startDate->copy()->addMonths(10);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
