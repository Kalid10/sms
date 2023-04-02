<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all admin users
        $admins = Admin::all();

        // Create 10 announcements for each admin
        $admins->each(function ($admin) {
            Announcement::factory()
                ->count(10)
                ->create([
                    'author_id' => $admin->id,
                ]);
        });
    }
}
