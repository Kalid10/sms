<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\StudentHelper;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Imports\AdminsRegistrationImport;
use App\Imports\StudentsRegistrationImport;
use App\Imports\TeachersRegistrationImport;
use App\Mail\AdminRegistered;
use App\Models\Admin;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;

class RegisterController extends Controller
{
    public function bulkRegisterStudents(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'user_file' => 'required|mimes:xlsx,xls,csv',
            'user_type' => 'required|in:student,teacher,admin',
        ]);

        try {
            // Check if the user has the permission to register students
            $this->checkRole($request->get('user_type'));

            // Set the expected headers and the import class based on the user type selected
            $expectedHeaders = [];
            $importClass = null;

            if ($request->get('user_type') === User::TYPE_STUDENT) {
                $expectedHeaders = [
                    'name',
                    'date_of_birth',
                    'gender',
                    'guardian_name',
                    'guardian_email',
                    'guardian_phone_number',
                    'guardian_gender',
                    'grade',
                ];
                $importClass = new StudentsRegistrationImport();
            }

            if ($request->get('user_type') === User::TYPE_TEACHER) {
                $expectedHeaders = [
                    'name',
                    'email',
                    'phone_number',
                    'gender',
                ];
                $importClass = new TeachersRegistrationImport();
            }

            if ($request->get('user_type') === USER::TYPE_ADMIN) {
                $expectedHeaders = [
                    'name',
                    'email',
                    'phone_number',
                    'gender',
                    'position',
                ];
                $importClass = new AdminsRegistrationImport();
            }

            // Validate the headers
            $this->validateHeaders($request->file('user_file'), $expectedHeaders);

            // Start the import queue
            $importClass->queue($request->file('user_file'));

            return redirect()->back();
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag());
        } catch (Exception $exception) {
            // If an error occurs, log the error and return an error message
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function register(RegisterRequest $request): Response|RedirectResponse
    {

        $existingGuardianId = $request->get('existing_guardian_id');

        // Check role
        $this->checkRole($request->get('type'));

        try {
            // Start transaction
            DB::beginTransaction();

            // Create user and related records based on the user type
            $user = match ($request->get('type')) {
                User::TYPE_TEACHER => $this->createTeacher($request),
                User::TYPE_STUDENT => $this->createStudent($request, $existingGuardianId),
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
        Teacher::create(['user_id' => $user->id,
            'leave_info' => json_encode([
                'total' => 3,
                'remaining' => 3,
            ]),
        ]);

        return $user;
    }

    private function createStudent(RegisterRequest $request, $existingGuardianId = null)
    {
        // If an existing guardian ID is provided, use it, otherwise create a new guardian
        if ($existingGuardianId) {
            $guardian = Guardian::find($existingGuardianId);
            if (! $guardian) {
                throw new Exception('Guardian not found!');
            }
        } else {
            // Create guardian
            $guardianUser = $this->createGuardian($request);
            $guardian = Guardian::create(['user_id' => $guardianUser->id]);
        }

        // Create student
        $studentUser = $this->createUser($request, User::TYPE_STUDENT);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'guardian_id' => $guardian->id,
            'guardian_relation' => $request->guardian_relation,
        ]);

        $assigned = StudentHelper::assignStudentToBatch($student->id, $request->input('level_id'));

        if (! $assigned) {
            // Rollback transaction
            DB::rollBack();
            throw new Exception('Batch full!');
        }

        return $studentUser;
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
        // Generate random password
        $newPassword = str::Password();

        $user = $this->createUser($request, null, $newPassword);
        $admin = Admin::create([
            'user_id' => $user->id,
            'position' => $request->input('position'),
        ]);

        Mail::to($user->email)->send(new AdminRegistered($admin, $newPassword));

        return $user;
    }

    private function createUser(RegisterRequest $request, string $type = null, $newPassword = null)
    {
        // if type is student, add birth_date to the request
        if ($type === User::TYPE_STUDENT) {
            $dateOfBirth = Carbon::parse($request->input('date_of_birth'));
            $dateOfBirth = $dateOfBirth->format('Y-m-d');

            // Filter validated data, like removing the excluding birthdate
            $validatedData = Arr::except($request->validated(), 'date_of_birth');

            // Create user
            return User::create(array_merge($validatedData,
                ['password' => Hash::make('secret'),
                    'date_of_birth' => $dateOfBirth,
                    'username' => StudentHelper::generateUsername($request->input('name'))]
            ));
        }

        return User::create(array_merge(
            $request->validated(),
            ['password' => Hash::make($newPassword || 'secret')]
        ));
    }

    public function checkRole($type): void
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

    private function validateHeaders(UploadedFile $file, array $expectedHeaders): void
    {
        $headings = (new HeadingRowImport)->toArray($file);

        foreach ($expectedHeaders as $expectedHeader) {
            if (! in_array($expectedHeader, $headings[0][0])) {
                $errors = ['headers' => ["Missing required header: {$expectedHeader}"]];
                throw ValidationException::withMessages($errors);
            }
        }
    }
}
