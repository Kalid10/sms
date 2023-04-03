<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Admin;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
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

    public function register(RegisterRequest $request)
    {
        // Check role
        $this->checkRole($request->get('type'));

        try {
            // Start transaction
            DB::beginTransaction();

            $guardian_user = null;
            // Check if the user is a student, if so, create guardian
            if ($request->input('type') === User::TYPE_STUDENT) {
                // Create user with guardian
                $guardian_user = User::create([
                    'name' => $request->input('guardian_name'),
                    'type' => User::TYPE_GUARDIAN,
                    'email' => $request->input('guardian_email'),
                    'phone_number' => $request->input('guardian_phone_number'),
                    'password' => Hash::make('secret'),
                    'gender' => 'male',
                ]);
            }

            $dateOfBirth = Carbon::parse($request->input('date_of_birth'));
            $dateOfBirth = $dateOfBirth->format('Y-m-d');

            // Filter validated data, like removing the excluding birthdate
            $validatedData = Arr::except($request->validated(), 'date_of_birth');

            // Create user
            $user = User::create(array_merge(
                $validatedData,
                ['password' => Hash::make('secret'), 'date_of_birth' => $dateOfBirth]
            ));

            // Add user to respective type table
            switch ($request->get('type')) {
                case User::TYPE_TEACHER:
                    // Create Teacher
                    Teacher::create([
                        'user_id' => $user->id,
                    ]);

                    // Commit transaction
                    DB::commit();
                    break;

                case User::TYPE_STUDENT:
                    if (! $guardian_user) {
                        // Rollback
                        DB::rollBack();

                        // Abort
                        abort(422, 'Guardian unknown!');
                    }

                    // Create Guardian
                    $guardian = Guardian::create([
                        'user_id' => $guardian_user->id,
                    ]);

                    // Create Student
                    Student::create([
                        'user_id' => $user->id,
                        'guardian_id' => $guardian->id,
                    ]);

                    // Commit transaction
                    DB::commit();
                    break;

                case User::TYPE_ADMIN:
                    // Create Admin
                    Admin::create([
                        'user_id' => $user->id,
                        'position' => $request->input('position'),
                    ]);

                    // Commit transaction
                    DB::commit();
                    break;

                default:
                    // Rollback
                    DB::rollBack();

                    // Abort
                    abort(422, 'Type unknown!');
                    break;
            }

            // Check if request is from inertia
            if ($request->header('X-Inertia')) {
                return redirect()->back()->with('success', ucfirst($request->input('type')).' created successfully.');
            }

            return response([
                'message' => 'User created successfully.',
                'user' => $user,
            ], 200);
        } catch (Exception $exception) {
            // Rollback transaction
            DB::rollBack();

            return response([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
