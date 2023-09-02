<?php

namespace App\Imports;

use App\Events\UserImportEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
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

class TeachersRegistrationImport implements ToModel, WithBatchInserts, WithHeadingRow, WithValidation, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;

    public function model(array $row)
    {
        // Start db transaction
        DB::beginTransaction();

        // Insert student user
        $teacherUserId = DB::table('users')->insertGetId([
            'name' => $row['name'],
            'type' => User::TYPE_TEACHER,
            'email' => $row['email'] ?? null,
            'phone_number' => $row['phone_number'] ?? null,
            'password' => Hash::make('secret'),
            'gender' => $row['gender'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert teacher
        DB::table('teachers')->insert([
            'user_id' => $teacherUserId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'leave_info' => [
                'total' => 3,
                'remaining' => 3,
            ],
        ]);

        // Commit, everything is good
        DB::commit();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required_without_all:phone_number,username|required_if:type,admin|email|unique:users',
            'phone_number' => 'required_without_all:email,username|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users',
            'gender' => ['required', Rule::in(['male', 'female', 'Male', 'Female'])],
        ];
    }

    public function chunkSize(): int
    {
        return env('STUDENT_IMPORT_CHUNK_SIZE', 100);
    }

    public function batchSize(): int
    {
        return env('STUDENT_IMPORT_BATCH_SIZE', 100);
    }

    public function registerEvents(): array
    {
        // Notify the user about the import status
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Event::dispatch(new UserImportEvent('info', 'Starting teacher data import in the background. You will be notified once the process is complete and the system records are updated.'));
            },
            ImportFailed::class => function (ImportFailed $event) {
                // Get validation exception
                $validationException = $event->getException();
                Event::dispatch(new UserImportEvent('error', $validationException->getMessage()));
            },
            AfterImport::class => function (AfterImport $event) {
                Event::dispatch(new UserImportEvent('success', 'Teacher import completed successfully.'));
            },
        ];
    }
}
