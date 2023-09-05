<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\SchoolYear;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::find(1)->id;

        foreach ($this->fakeData() as $data) {
            Announcement::create($data);
        }

    }

    protected function fakeData()
    {
        return [
            [
                'title' => 'Annual administrative meeting',
                'body' => 'Discuss the goals and priorities for the upcoming year. It is also a chance for administrators to share ideas and get to know each other better.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(7),
                'target_group' => ['admin'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Teachers workshop',
                'body' => 'Workshop are designed to help teachers improve their skills and knowledge. They can cover topics such as teaching methods, classroom management, and curriculum development.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(5),
                'target_group' => ['teachers'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Science Fair',
                'body' => 'The annual Science Fair will be held on April 5th. Students are encouraged to showcase their science projects and experiments.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(3),
                'target_group' => ['Admin', 'guardians', 'teachers'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'School closed for Labor Day',
                'body' => 'School will be closed on September 6th in observance of Labor Day. Classes will resume on September 7th.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(3),
                'target_group' => ['all'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Back to School Day',
                'body' => 'Join us for Back-to-School on September 15th at 10:00 AM in the school auditorium. Meet your child’s teachers and learn about the upcoming school year.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(5),
                'target_group' => ['guardians'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Parent-Teacher Conferences',
                'body' => 'Parent-Teacher Conferences will be held on October 20th and 21st. Sign up for appointments with your child’s teachers through the school website.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(10),
                'target_group' => ['guardians'.'teachers'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Book Fair Week',
                'body' => 'Get ready for the annual Book Fair from November 5th to November 9th in the school library. Browse a wide selection of books and support our school.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(7),
                'target_group' => ['teachers', 'guardians'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Math competition:',
                'body' => 'A math competition is a test of mathematical skills. Math competitions typically test students knowledge of basic math concepts, as well as their problem-solving skills.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(3),
                'target_group' => ['all'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Semester Midterm Exams',
                'body' => 'Midterm exams will be held from January 10th to January 14th. Study hard and prepare for your exams!',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(8),
                'target_group' => ['students'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Semester Final Exams ',
                'body' => 'The annual Science Fair will be held on April 5th. Students are encouraged to showcase their science projects and experiments.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(3),
                'target_group' => ['Admin', 'guardians', 'teachers'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],

            [
                'title' => 'Semester Break',
                'body' => 'Enjoy your Break from March 20th to March 24th. Take time to relax and recharge for the remainder of the school year.',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(5),
                'target_group' => ['all'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],
            [
                'title' => 'Graduation Ceremony',
                'body' => 'Our Graduation Ceremony will take place on June 9th at 10:00 AM in the school stadium. Congratulations to the Class of 2023!',
                'author_id' => User::find(1)->id,
                'expires_on' => Carbon::now()->addDays(15),
                'target_group' => ['admin', 'guardians', 'teachers'],
                'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? SchoolYear::factory()->create(['end_date' => null])->id,
            ],

        ];

    }
}
