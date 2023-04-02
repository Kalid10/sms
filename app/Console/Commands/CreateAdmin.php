<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin {name} {email} {gender}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $validationResult = $this->validate();

            if ($validationResult !== 0) {
                return $validationResult;
            }
            DB::beginTransaction();

            $user = User::create([
                'name' => $this->argument('name'),
                'email' => $this->argument('email'),
                'password' => Hash::make('secret'),
                'type' => User::TYPE_ADMIN,
                'gender' => $this->argument('gender'),
            ]);

            // Get all roles and attach them to the user
            $user->roles()->attach(Role::all()->pluck('name'));

            DB::commit();
            $this->info('Admin registration succeeded.');

            return 0;
        } catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());

            return 1;
        }
    }

    private function validate(): int
    {
        $validator = Validator::make([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'gender' => $this->argument('gender'),
        ], [
            'name' => ['required'],
            'email' => ['required', 'unique:users,email', 'email'],
            'gender' => ['required', 'in:male,female'],
        ]);

        if ($validator->fails()) {
            $this->info('Admin registration failed. See error(s) below.');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        return 0;
    }
}
