<?php

namespace App\Console\Commands;

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
    protected $signature = 'app:create-admin {name} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            if ($this->validate()) {
                DB::beginTransaction();

                $user = User::create([
                    'name' => $this->argument('name'),
                    'email' => $this->argument('email'),
                    'password' => Hash::make('secret'),
                ]);

                // Attach new User to Admin, assign `manage-roles` Role
                $user->roles()->attach('manage-roles');

                DB::commit();
                $this->info('Admin registration succeeded.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    private function validate(): bool
    {
        $validator = Validator::make([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
        ], [
            'name' => ['required'],
            'email' => ['required', 'unique:users,email', 'email'],
        ]);

        if ($validator->fails()) {
            $this->info('Admin registration failed. See error(s) below.');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }
}
