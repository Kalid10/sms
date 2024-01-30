<?php

namespace App\Imports;

use App\Events\UserImportEvent;
use App\Helpers\StudentHelper;
use App\Models\Batch;
use App\Models\Level;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
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
        // Extract grade and section from the input row
        $gradeWithSection = $row['grade'];

        // Split the grade to separate grade and section
        [$grade, $section] = explode('-', $gradeWithSection, 2);

        // Check if the level exists
        $level = Level::where('name', $grade)->first();

        if (! $level) {
            throw new Exception("Grade {$grade} does not exist");
        }

        // Get the batch ID based on the level name and batch name
        $batchId = Batch::where('level_id', $level->id)
            ->where('section', $section)
            ->first()->id;

        // Start db transaction
        DB::beginTransaction();

        $guardianPhoneNumber = $row['mother_phone_number'] ?? $row['father_phone_number'] ?? $row['other_guardian_phone_number'];

        // Upsert guardian user
        DB::table('users')->updateOrInsert(
            [
                'phone_number' => $guardianPhoneNumber,
                'type' => User::TYPE_GUARDIAN,
            ],
            [
                'email' => $row['guardian_email'],
                'name' => $row['guardian_name'],
                'password' => Hash::make('secret'),
                'gender' => strtolower($this->getGuardianGenderOrRelation($row)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Get guardian user id
        $guardianUser = DB::table('users')->where('phone_number', $guardianPhoneNumber)->first();

        // Upsert guardian
        DB::table('guardians')->updateOrInsert(
            ['user_id' => $guardianUser->id],
            [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        );

        // Get guardian id
        $guardian = DB::table('guardians')->where('user_id', $guardianUser->id)->first();

        // Parse birthdate
        $dateOfBirth = Carbon::parse($row['date_of_birth'])->format('Y-m-d');

        // Check if a student with the same name, date of birth, and guardian email exists
        $existingStudent = DB::table('users')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('guardians', 'students.guardian_id', '=', 'guardians.id')
            ->join('users as guardian_users', 'guardians.user_id', '=', 'guardian_users.id')
            ->where('users.name', $row['name'])
            ->where('users.date_of_birth', $dateOfBirth)
            ->where('users.type', User::TYPE_STUDENT)
            ->where('guardian_users.email', $row['guardian_email'])
            ->select('users.*')
            ->first();

        $studentUserName = $existingStudent
            ? $existingStudent->username
            : ($row['username'] ?? StudentHelper::generateUsername($row['name']));

        // Upsert student user
        DB::table('users')->updateOrInsert(
            ['username' => $studentUserName, 'email' => $row['email'] ?? null, 'type' => User::TYPE_STUDENT],
            [
                'name' => $row['name'],
                'date_of_birth' => $dateOfBirth,
                'password' => Hash::make('secret'),
                'gender' => strtolower($row['gender']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Get student user id
        $studentUser = DB::table('users')->where('username', $studentUserName)->first();

        // Upsert student
        DB::table('students')->updateOrInsert(
            ['user_id' => $studentUser->id,
                'guardian_id' => $guardian->id, ],
            [
                'guardian_relation' => $this->getGuardianGenderOrRelation($row, false),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        );

        // Get student id
        $student = DB::table('students')->where('user_id', $studentUser->id)->first();

        // Assign the student to a batch
        $assign = StudentHelper::assignStudentToBatch($student->id, $batchId);

        // Throw exception
        if (! $assign) {
            // Rollback everything
            DB::rollBack();
            throw new Exception('Batch full!');
        }

        // Commit, everything is good
        DB::commit();
    }

    public function chunkSize(): int
    {
        return env('STUDENT_IMPORT_CHUNK_SIZE', 50);
    }

    public function batchSize(): int
    {
        return env('STUDENT_IMPORT_BATCH_SIZE', 50);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['male', 'female', 'Male', 'Female'])],
            'email' => 'nullable|email',
            'guardian_name' => 'required|string|max:255',
            'guardian_email' => 'nullable|email',
            'mother_phone_number' => 'nullable|max:20|regex:/(09)[0-9]{8}/|max:10|min:10',
            'father_phone_number' => 'nullable|max:20|regex:/(09)[0-9]{8}/|max:10|min:10',
            'other_guardian_phone_number' => ['required_if:father_phone_number,null|required_if:mother_phone_number,null', 'nullable', 'max:20', 'regex:/(09)[0-9]{8}/', 'max:10', 'min:10'],
            'username' => 'nullable|string|max:255',
            'other_guardian_gender' => ['required_if:other_guardian_phone_number,!=, null', 'nullable', Rule::in(['male', 'female', 'Male', 'Female'])],
        ];
    }

    public function registerEvents(): array
    {
        // Notify the user about the import status
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Event::dispatch(new UserImportEvent('info', 'Starting student data import in the background. You will be notified once the process is complete and the system records are updated.'));
            },
            ImportFailed::class => function (ImportFailed $event) {
                // Get validation exception
                $validationException = $event->getException();
                Log::error($validationException);
                Event::dispatch(new UserImportEvent('error', $validationException->getMessage()));
            },
            AfterImport::class => function (AfterImport $event) {
                Event::dispatch(new UserImportEvent('success', 'Student import completed successfully.'));
            },
        ];
    }

    private function getGuardianGenderOrRelation($row, $isGender = true)
    {
        if ($row['mother_phone_number']) {
            return $isGender ? 'female' : 'mother';
        }

        if ($row['father_phone_number']) {
            return $isGender ? 'male' : 'father';
        }

        return $isGender ? $row['other_guardian_gender'] : $row['other_guardian_relation'];
    }
}
