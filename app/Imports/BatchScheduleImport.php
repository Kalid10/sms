<?php

namespace App\Imports;

use App\Events\BatchScheduleImportEvent;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\SchoolPeriod;
use App\Models\Subject;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class BatchScheduleImport implements ToModel, WithBatchInserts, WithHeadingRow, WithValidation, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;

    public function model(array $row)
    {
        // Start db transaction
        DB::beginTransaction();

        // Extract grade and section from the input row
        $periodNameWithDay = $row['day_period_name'];

        // Split the period_name to separate day and period
        [$day, $period_name] = explode('_', $periodNameWithDay, 2);

        // Check if day is valid
        if (! in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])) {
            $this->throwException("Day {$day} is not valid");
        }

        // Check if the period name is numeric
        if (! is_numeric($period_name)) {
            $this->throwException("Period name {$period_name} is not valid");
        }

        // Loop through the row
        foreach ($row as $key => $value) {
            if ($key === 'period_name') {
                continue;
            }

            if ($value === null) {
                continue;
            }

            // Split the period_name to separate day and period
            [$levelName, $section] = explode('_', $key, 2);

            // Check if the level exists
            $level = Level::where('name', $levelName)->first();

            if ($level) {
                $level->load('levelCategory');

                // Check if the batch exists with the level id and section
                $batch = Batch::where('level_id', $level->id)->where('section', $section)->first();

                if ($batch) {

                    $batchSubject = $this->checkIfBatchSubjectExists($batch->id, $value);

                    if (! $batchSubject) {
                        DB::rollBack();
                        throw new Exception("Subject {$value} is not valid for batch {$levelName}-{$section}");
                    }
                    // Get the batch ID based on the level name and batch name
                    $batchId = $batch->id;

                    // Get the school period ID based on the period name
                    $schoolPeriodId = SchoolPeriod::where('name', $period_name)->where('level_category_id', $level->levelCategory->id)->first()->id;

                    // Delete if schedule exists
                    $this->deleteIfScheduleExists($batchId, $schoolPeriodId, $day);

                    // Create a school schedule
                    BatchSchedule::create([
                        'batch_id' => $batchId,
                        'school_period_id' => $schoolPeriodId,
                        'day_of_week' => $day,
                        'batch_subject_id' => $batchSubject->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Commit db transaction
                    DB::commit();
                }
            }
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            'day_period_name' => 'required|string|max:255',
        ];
    }

    public function registerEvents(): array
    {
        // Notify the user about the import status
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Event::dispatch(new BatchScheduleImportEvent('info', 'Starting class schedule import in the background. You will be notified once the process is complete and the system records are updated.'));
            },
            ImportFailed::class => function (ImportFailed $event) {
                // Get validation exception
                $validationException = $event->getException();
                Log::error($validationException);
                Event::dispatch(new BatchScheduleImportEvent('error', $validationException->getMessage()));
            },
            AfterImport::class => function (AfterImport $event) {
                Event::dispatch(new BatchScheduleImportEvent('success', 'Student import completed successfully.'));
            },
        ];
    }

    private function checkIfBatchSubjectExists($batchId, $subjectName)
    {
        $subject = Subject::where('full_name', $subjectName)->orWhere('short_name', $subjectName)->first();

        if ($subject) {
            return BatchSubject::where('batch_id', $batchId)->where('subject_id', $subject->id)->first();
        }

        return null;
    }

    private function deleteIfScheduleExists($batchId, $schoolPeriodId, $dayOfWeek): void
    {
        $schedule = BatchSchedule::where('batch_id', $batchId)->where('school_period_id', $schoolPeriodId)->where('day_of_week', $dayOfWeek)->first();

        if ($schedule) {
            $schedule->delete();
        }
    }

    private function throwException($message)
    {
        // Rollback everything
        DB::rollBack();

        Event::dispatch(new BatchScheduleImportEvent('error', $message));

        throw new Exception($message);
    }
}
