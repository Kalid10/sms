<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\HomeroomTeacher;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeroomTeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HomeroomTeacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $teacher = Teacher::inRandomOrder()->first() ?? Teacher::factory()->create();
        $batch = Batch::inRandomOrder()->first() ?? Batch::factory()->create();

        return [
            'teacher_id' => $teacher->id,
            'batch_id' => $batch->id,
        ];
    }
}
