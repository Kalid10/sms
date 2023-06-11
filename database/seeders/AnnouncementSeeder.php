<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::find(1);

        Announcement::factory()
            ->count(10)
            ->create([
                'author_id' => $admin->id,
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id,
            ]);
    }
}
