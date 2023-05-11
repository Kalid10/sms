<?php

namespace Database\Seeders\Simulation;

use App\Facades\Ethiopian;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\SimulationSeeder;

class FamilySeeder extends SimulationSeeder
{
    private array $multipleChildrenGuardians = [];

    public function run(): void
    {
        /**
         * Student count is set as the extremes of having
         * 3 sections with a minmax of [17, 40] students
         * per section in 15 levels.
         */
        $studentsCount = fake()->numberBetween(1000, 1800);

        for ($i = 1; $i < $studentsCount + 1; $i++) {
            /**
             * Every 12 student is a student with a sibling in the school.
             * Every 60 student is a student with more than 1 sibling.
             */
            $isSiblingChild = false;

            if ($i % 75 === 0) {
                $guardian = Guardian::inRandomOrder()->first();

                if ($i % 250 === 0) {
                    $guardian = Guardian::find(array_rand(array_merge($this->multipleChildrenGuardians, [$guardian->id])));
                } else {
                    $this->multipleChildrenGuardians[] = $guardian->id;
                }

                $isSiblingChild = true;
            } else {
                $gender = $this->gender();
                $fullName = Ethiopian::fullName($gender);

                $guardian = Guardian::create([
                    'user_id' => User::create([
                        'name' => $fullName,
                        'email' => Ethiopian::email($fullName),
                        'username' => Ethiopian::username($fullName),
                        'phone_number' => Ethiopian::phoneNumber(),
                        'gender' => $gender,
                        'date_of_birth' => fake()->date('Y-m-d', '-'.(($i % 15) + rand(-2, 6) + 24).' years'),
                        'type' => User::TYPE_GUARDIAN,
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    ])->id,
                ]);
            }

            $gender = $this->gender();
            $fullName = Ethiopian::fullName($gender);

            $student = Student::create([
                'user_id' => User::create([
                    'name' => $fullName,
                    'username' => 'GYA/'.fake()->unique()->numberBetween(10000, 99999),
                    'phone_number' => rand(1, 5) % 2 === 0 ? Ethiopian::phoneNumber() : null,
                    'gender' => $gender,
                    'date_of_birth' => fake()->dateTimeBetween('-'.(($i % 15) + 4).' years', '-'.(($i % 15) + 3).' years')->format('Y-m-d'),
                    'type' => User::TYPE_STUDENT,
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                ])->id,
                'guardian_id' => $guardian->id,
                'guardian_relation' => $isSiblingChild ? $guardian->children->first()->guardian_relation : Ethiopian::relative($guardian->user->gender),
            ]);

            $student->guardian()->associate($guardian)->save();
        }
    }
}
