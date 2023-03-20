<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Admin;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Create user
            $user = User::create(array_merge(
                $request->validated(),
                ['password' => Hash::make('secret')]
            ));

            // Add user to respective type table
            switch ($request->get('type')) {
                case User::TYPE_GUARDIAN:
                    // Create Guardian
                    Guardian::create([
                        'user_id' => $user->id,
                    ]);

                    // Commit transaction
                    DB::commit();
                    break;

                case User::TYPE_TEACHER:
                    // Create Teacher
                    Teacher::create([
                        'user_id' => $user->id,
                    ]);

                    // Commit transaction
                    DB::commit();
                    break;

                case User::TYPE_STUDENT:
                    // Create Student
                    Student::create([
                        'user_id' => $user->id,
                        'guardian_id' => $request->has('guardian_id'),
                    ]);

                    // Commit transaction
                    DB::commit();
                    break;

                case User::TYPE_ADMIN:
                    // Create Admin
                    Admin::create([
                        'user_id' => $user->id,
                        'position' => $request->has('position'),
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
                return Inertia::render('Welcome');
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
