<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\HomeroomTeacher;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class HomeroomTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = Teacher::all();
        $batches = Batch::all();

        foreach ($batches as $batch) {
            if (! $batch->homeroomTeacher) {
                $teacher = $teachers->random();
                HomeroomTeacher::create([
                    'teacher_id' => $teacher->id,
                    'batch_id' => $batch->id,
                ]);
            }
        }
    }
}
