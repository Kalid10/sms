<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Admin;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): Response|RedirectResponse
    {
        // Check role
        $this->checkRole($request->get('type'));

        try {
            // Start transaction
            DB::beginTransaction();

            // Create user and related records based on the user type
            $user = match ($request->get('type')) {
                User::TYPE_TEACHER => $this->createTeacher($request),
                User::TYPE_STUDENT => $this->createStudent($request),
                User::TYPE_ADMIN => $this->createAdmin($request),
                default => throw new Exception('Type unknown!'),
            };

            // Commit transaction
            DB::commit();

            // Return a success message
            return $this->successResponse($request, $user);
        } catch (Exception $exception) {
            // Rollback transaction
            DB::rollBack();

            Log::error($exception->getMessage());
            if ($exception->getMessage() === 'Batch full!') {
                return $this->errorResponse($request, 'All batches are full! Contact Admin!');
            }

            return $this->errorResponse($request, 'Something went wrong!');
        }
    }

    private function createTeacher(RegisterRequest $request)
    {
        $user = $this->createUser($request);
        Teacher::create(['user_id' => $user->id]);

        return $user;
    }

    private function createStudent(RegisterRequest $request)
    {
        // Create guardian
        $guardian_user = $this->createGuardian($request);

        $guardian = Guardian::create(['user_id' => $guardian_user->id]);

        // Create student
        $student_user = $this->createUser($request, User::TYPE_STUDENT);
        $student = Student::create([
            'user_id' => $student_user->id,
            'guardian_id' => $guardian->id,
        ]);

        // Assign student to batch
        $assigned = $this->assignStudentToBatch($student, $request->input('level_id'));

        if (! $assigned) {
            // Rollback transaction
            DB::rollBack();
            throw new Exception('Batch full!');
        }

        return $student_user;
    }

    private function createGuardian(RegisterRequest $request)
    {
        return User::create([
            'name' => $request->input('guardian_name'),
            'type' => User::TYPE_GUARDIAN,
            'email' => $request->input('guardian_email'),
            'phone_number' => $request->input('guardian_phone_number'),
            'password' => Hash::make('secret'),
            'gender' => $request->input('guardian_gender'),
        ]);
    }

    private function createAdmin(RegisterRequest $request)
    {
        $user = $this->createUser($request);
        Admin::create([
            'user_id' => $user->id,
            'position' => $request->input('position'),
        ]);

        return $user;
    }

    private function createUser(RegisterRequest $request, string $type = null)
    {
        // if type is student, add birth_date to the request
        if ($type === User::TYPE_STUDENT) {
            $dateOfBirth = Carbon::parse($request->input('date_of_birth'));
            $dateOfBirth = $dateOfBirth->format('Y-m-d');

            Log::info($dateOfBirth);

            // Filter validated data, like removing the excluding birthdate
            $validatedData = Arr::except($request->validated(), 'date_of_birth');

            Log::info($validatedData['gender']);
            // Create user
            return User::create(array_merge(
                $validatedData,
                ['password' => Hash::make('secret'), 'date_of_birth' => $dateOfBirth]
            ));
        }

        return User::create(array_merge(
            $request->validated(),
            ['password' => Hash::make('secret')]
        ));
    }

    public function checkRole($type)
    {
        if (auth()->user()->hasRole('manage-users')) {
            return;
        } else {
            // Check type and apply middleware
            switch ($type) {
                case User::TYPE_ADMIN:
                    if (! auth()->user()->hasRole('manage-admins')) {
                        abort(403, 'You are not authorized to perform this action!');
                    }
                    break;
                case User::TYPE_TEACHER:
                    if (! auth()->user()->hasRole('manage-teachers')) {
                        abort(403, 'You are not authorized to perform this action!');
                    }
                    break;
                case User::TYPE_STUDENT:
                    if (! auth()->user()->hasRole('manage-students')) {
                        abort(403, 'You are not authorized to perform this action!');
                    }
                    break;
                case User::TYPE_GUARDIAN:
                    if (! auth()->user()->hasRole('manage-guardians')) {
                        abort(403, 'You are not authorized to perform this action!');
                    }
                    break;
                default:
                    abort(422, 'Type unknown!');
            }
        }
    }

    private function assignStudentToBatch(Student $student, $levelId): bool
    {
        // Find all the batches in the student's level
        $batches = Batch::where('level_id', $levelId)->get();

        // Initialize variables to store the minimum number of students and the target batch
        $minStudentCount = PHP_INT_MAX;
        $targetBatch = null;

        // Loop through the batches and find the batch with the minimum number of students
        foreach ($batches as $batch) {
            $studentCount = BatchStudent::where('batch_id', $batch->id)->count();

            if ($studentCount < $minStudentCount && $studentCount < $batch->max_students) {
                $minStudentCount = $studentCount;
                $targetBatch = $batch;
            }
        }

        // If a target batch is found, assign the student to the batch
        if ($targetBatch) {
            BatchStudent::create([
                'batch_id' => $targetBatch->id,
                'student_id' => $student->id,
            ]);

            return true;
        }

        // If no suitable batch is found, return false
        return false;
    }

    private function successResponse(RegisterRequest $request, User $user): Response|RedirectResponse
    {
        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', ucfirst($request->input('type')).' created successfully.');
        }

        return response([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 200);
    }

    private function errorResponse(RegisterRequest $request, string $message): Response|RedirectResponse
    {
        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('error', $message);
        }

        return response([
            'message' => $message,
        ], 422);
    }
}
