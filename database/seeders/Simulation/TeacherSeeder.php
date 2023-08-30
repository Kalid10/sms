<?php

namespace Database\Seeders\Simulation;

use App\Facades\Ethiopian;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\Simulation\TeacherSeeder\AmharicTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\BiologyTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\BusinessTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\ChemistryTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\ComputerScienceTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\EconomicsTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\EnglishTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\ExtraCurricularTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\GeographyTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\HistoryTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\MathsTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\NapTimeTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\PETeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\PhysicsTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\SocialStudiesTeacherSeeder;
use Database\Seeders\Simulation\TeacherSeeder\TDTeacherSeeder;
use Database\Seeders\SimulationSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TeacherSeeder extends SimulationSeeder
{
    protected static Collection $batchSubjects;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::$batchSubjects = $this->batchSubjects();

        //        // When you want to seed Teachers without Batch Subject assigned
        //        for ($i = 0; $i < 80; $i++) {
        //            $this->createTeacher();
        //        }

        // When you want to seed a teacher with Batch Subject assigned
        $this->call([
            AmharicTeacherSeeder::class,
            BiologyTeacherSeeder::class,
            BusinessTeacherSeeder::class,
            ChemistryTeacherSeeder::class,
            ComputerScienceTeacherSeeder::class,
            EconomicsTeacherSeeder::class,
            EnglishTeacherSeeder::class,
            ExtraCurricularTeacherSeeder::class,
            GeographyTeacherSeeder::class,
            HistoryTeacherSeeder::class,
            MathsTeacherSeeder::class,
            NapTimeTeacherSeeder::class,
            PETeacherSeeder::class,
            PhysicsTeacherSeeder::class,
            SocialStudiesTeacherSeeder::class,
            TDTeacherSeeder::class,
        ]);
    }

    protected function create(
        string $subject, array|string $level = null, array|string $level_category = null, array|string $section = null
    ): void {
        $teacher = $this->createTeacher();
        $this->subject($subject, $level, $level_category, $section)->each(function ($batchSubject) use ($teacher) {
            $batchSubject->teacher()->associate($teacher);
            $batchSubject->save();
        });
    }

    protected function createTeacher()
    {
        $gender = $this->gender();
        $fullName = Ethiopian::fullName($gender);

        return Teacher::create([
            'user_id' => User::create([
                'name' => $fullName,
                'email' => Ethiopian::email($fullName),
                'username' => Ethiopian::username($fullName),
                'profile_image' => 'https://avatars.dicebear.com/api/open-peeps/'.Str::camel($fullName).'.svg',
                'phone_number' => Ethiopian::phoneNumber(),
                'gender' => $gender,
                'date_of_birth' => fake()->date('Y-m-d', '-'.(rand(-2, 6) + 24).' years'),
                'type' => User::TYPE_TEACHER,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            ])->id,
            'leave_info' => json_encode([
                'total' => 3,
                'remaining' => 3,
            ]),
        ]);
    }

    protected function subject(
        string $subject, array|string $level = null, array|string $level_category = null, array|string $section = null
    ): Collection {
        return self::$batchSubjects
            ->where('subject.full_name', $subject)
            ->when($level, function ($query) use ($level) {
                if (is_array($level)) {
                    return $query->whereIn('batch.level.name', $level);
                }

                return $query->where('batch.level.name', $level);
            })
            ->when($level_category, function ($query) use ($level_category) {
                if (is_array($level_category)) {
                    return $query->whereIn('batch.level.levelCategory.name', $level_category);
                }

                return $query->where('batch.level.levelCategory.name', $level_category);
            })
            ->when($section, function ($query) use ($section) {
                if (is_array($section)) {
                    return $query->whereIn('batch.section', $section);
                }

                return $query->where('batch.section', $section);
            });
    }

    protected function batches(): Collection
    {
        if (Batch::count() < 1) {
            $this->call(BatchSeeder::class);
        }

        return Batch::with('levels.levelCategory')->get();
    }

    protected function batchSubjects(): Collection
    {
        if (BatchSubject::count() < 1) {
            $this->call(BatchSubjectSeeder::class);
        }

        return BatchSubject::with('subject', 'batch.level.levelCategory')->get();
    }
}
