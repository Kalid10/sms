<?php

namespace App\Imports;

use App\Helpers\StudentHelper;
use App\Models\Level;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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

class StudentsRegistrationImport implements ToModel, WithBatchInserts, WithHeadingRow, WithValidation, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;

    public function model(array $row)
    {
        // Check if level exists
        $level = Level::where('name', $row['grade']);
        if (! $level->exists()) {
            throw new Exception("Level {$row['grade']} does not exist");
        }

        // Get the level ID based on the level name
        $levelId = $level->first()->id;

        // Start db transaction
        DB::beginTransaction();

        // Insert guardian user
        $guardianUserId = DB::table('users')->insertGetId([
            'name' => $row['guardian_name'],
            'type' => User::TYPE_GUARDIAN,
            'phone_number' => $row['guardian_phone_number'],
            'email' => $row['guardian_email'],
            'password' => Hash::make('secret'),
            'gender' => $row['guardian_gender'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert guardian
        $guardianId = DB::table('guardians')->insertGetId([
            'user_id' => $guardianUserId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Parse birthdate
        $dateOfBirth = Carbon::parse($row['date_of_birth']);
        $dateOfBirth = $dateOfBirth->format('Y-m-d');

        // Insert student user
        $studentUserId = DB::table('users')->insertGetId([
            'name' => $row['name'],
            'type' => User::TYPE_STUDENT,
            'email' => $row['email'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'password' => Hash::make('secret'),
            'gender' => $row['gender'],
            'username' => StudentHelper::generateUsername($row['name']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert student
        $studentId = DB::table('students')->insertGetId([
            'user_id' => $studentUserId,
            'guardian_id' => $guardianId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Assign the student to a batch
        $assign = StudentHelper::assignStudentToBatch($studentId, $levelId);

        // Throw exception
        if (! $assign) {
            // Rollback everything
            DB::rollBack();
            throw new Exception('Batch full!');
        }

        // Commit, everything is good
        DB::commit();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['male', 'female', 'Male', 'Female'])],
            'guardian_name' => 'required|string|max:255',
            'guardian_email' => 'nullable|email|unique:users,email,0',
            'guardian_phone_number' => 'required|max:20|unique:users,phone_number,0',
            'username' => 'nullable|string|max:255|unique:users,username,0',
            'guardian_gender' => ['required', Rule::in(['male', 'female', 'Male', 'Female'])],
        ];
    }

    public function chunkSize(): int
    {
        // TODO: Change the number of rows to be read at a time, after testing
        return 100;
    }

    public function batchSize(): int
    {
        // TODO: Change the number of rows to be inserted at a time, after testing
        return 100;
    }

    public function registerEvents(): array
    {
        // TODO: Notify front-end
        return [
            BeforeImport::class => function (BeforeImport $event) {
                // Do something before the import starts
                Log::info('Import started');
            },
            ImportFailed::class => function (ImportFailed $event) {
                // Handle the exception how you'd like.
                Log::error($event->getException());
            },
            AfterImport::class => function (AfterImport $event) {
                // Do something after the import finished
                Log::info('Import finished');
            },
        ];
    }
}
