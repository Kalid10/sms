<?php

namespace Database\Seeders\Simulation;

use App\Models\Batch;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeroomTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Run through all the Batches in record
        // For each batch, query all its teachers
        // and select a random Teacher
        // Assign this teacher as the homeroom teacher

        try {
            DB::beginTransaction();

            $batches = Batch::active();

            $batches->each(function ($batch) {
                $teachers = $batch->load('teachers')->teachers;
                $randomTeacher = $teachers->random();
                $batch->homeroomTeacher()->create([
                    'teacher_id' => $randomTeacher->id,
                    'batch_id' => $batch->id,
                ]);
            });

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            Log::info('Error while seeding HomeroomTeacherSeeder: '.$exception->getMessage());
        }
    }
}
