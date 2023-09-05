<?php

namespace Database\Seeders;

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SchoolScheduleSeeder extends Seeder
{
    //    public function run()
    //    {
    //        $schoolYears = SchoolYear::all();
    //
    //        $schoolYears->each(function ($schoolYear, $key) {
    //            $numberOfSchedules = $schoolYear->id === 4 ? 15 : 10;
    //
    //            for ($i = 0; $i < $numberOfSchedules; $i++) {
    //                $startDate = now()->addDays($i);
    //                $endDate = $startDate->copy();
    //
    //                $schedulesPerDay = rand(1, 4);
    //                for ($j = 0; $j < $schedulesPerDay; $j++) {
    //                    $title = $startDate->format('l') . ' Schedule ' . ($j + 1);
    //                    SchoolSchedule::factory()->create([
    //                        'school_year_id' => $schoolYear->id,
    //                        'start_date' => $startDate,
    //                        'end_date' => $endDate,
    //                        'title' => $title,
    //                    ]);
    //                }
    //            }
    //        });
    //    }

    public function run()
    {
        $schoolYears = SchoolYear::all();
        $schoolYears->each(function ($schoolYear, $key) {
            $data = $this->fakeData();
            foreach ($data as $item) {
                SchoolSchedule::create(array_merge($item, [
                    'school_year_id' => $schoolYear->id,
                ],

                ));
            }
        });
    }

    //    half_closed , not_closed , closed
    protected function fakeData()
    {
        date_default_timezone_set('UTC'); // Set the default timezone to UTC

        return [
            [
                'title' => 'Ethiopian New Year (Enkutatash)',
                'body' => 'Enkutatash is the word for the Ethiopian New Year in Amharic, the official language of Ethiopia, while it is called Ri’se Awde Amet (“Head Anniversary”) in Ge’ez, the term preferred by the Ethiopian Orthodox Tewahedo Church. It occurs on Meskerem 1 on the Ethiopian calendar, which is 11 September (or, during a leap year, 12 September) according to the Gregorian calendar.',
                'start_date' => Carbon::parse('2023-09-12 00:00:00'),
                'end_date' => Carbon::parse('2023-09-12 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'celebration'],
            ],
            [
                'title' => 'Meskel (Finding of the True Cross)',
                'body' => 'Meskel is an annual religious holiday in the Ethiopian Orthodox and Eritrean Orthodox churches, which commemorates the discovery of the True Cross by the Roman Empress Helena (Saint Helena) in the fourth century. Meskel occurs on the 17 Meskerem in the Ethiopian calendar (27 or 28 September in the Gregorian calendar, depending on the year).',
                'start_date' => Carbon::parse('2023-09-27 00:00:00'),
                'end_date' => Carbon::parse('2023-09-27 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'religious'],
            ],
            [
                'title' => 'Timket (Epiphany)',
                'body' => 'Timkat (Amharic: ጥምቀት which means "baptism") (also spelled Timket, or Timqat) is the Ethiopian Orthodox celebration of Epiphany. It is celebrated on January 19 (or 20 on Leap Year), corresponding to the 10th day of Terr following the Ethiopian calendar. Timket celebrates the baptism of Jesus in the Jordan River. This festival is best known for its ritual reenactment of baptism (similar to such reenactments performed by numerous Christian pilgrims to the Holy Land when they visit the Jordan).',
                'start_date' => Carbon::parse('2024-01-19 00:00:00'),
                'end_date' => Carbon::parse('2024-01-19 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'religious'],
            ],
            [
                'title' => 'Adwa Victory Day',
                'body' => 'Adwa Victory Day is a national holiday in Ethiopia, occurring on 2 March, commemorating the decisive defeat of Italian colonial forces by Ethiopian forces at the Battle of Adwa in 1896. It is the most important national holiday in Ethiopia along with the Ethiopian New Year (Enkutatash) festival.',
                'start_date' => Carbon::parse('2024-03-02 00:00:00 '),
                'end_date' => Carbon::parse('2024-03-02 00:00:00 '),
                'type' => 'closed',
                'tags' => ['holiday', 'celebration'],
            ],
            [
                'title' => 'Good Friday',
                'body' => 'Good Friday is a Christian holiday commemorating the crucifixion of Jesus and his death at Calvary. It is observed during Holy Week as part of the Paschal Triduum on the Friday preceding Easter Sunday, and may coincide with the Jewish observance of Passover. It is also known as Holy Friday, Great Friday, Great and Holy Friday (also Holy and Great Friday), and Black Friday.',
                'start_date' => Carbon::parse('2024-04-14 00:00:00'),
                'end_date' => Carbon::parse('2024-04-14 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'religious'],
            ],
            [
                'title' => 'Easter',
                'body' => 'Easter, also called Pascha (Greek, Latin) or Resurrection Sunday, is a festival and holiday commemorating the resurrection of Jesus from the dead, described in the New Testament as having occurred on the third day after his burial following his crucifixion by the Romans at Calvary c. 30 AD. It is the culmination of the Passion of Jesus, preceded by Lent (or Great Lent), a 40-day period of fasting, prayer, and penance.',
                'start_date' => Carbon::parse('2024-04-16 00:00:00'),
                'end_date' => Carbon::parse('2024-04-16 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'religious'],
            ],
            [
                'title' => 'International Workers Day',
                'body' => 'International Workers Day, also known as Labour Day in most countries and often referred to as May Day, is a celebration of labourers and the working classes that is promoted by the international labour movement and occurs every year on May Day (1 May).',
                'start_date' => Carbon::parse('2024-05-01 00:00:00'),
                'end_date' => Carbon::parse('2024-05-01 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'celebration'],
            ],
            [
                'title' => 'Prophet Muhammad\'s Birthday',
                'body' => 'Mawlid or Mawlid al-Nabi al-Sharif (Arabic: مَولِد النَّبِي‎ mawlidu n-nabiyyi, "Birth of the Prophet", sometimes simply called in colloquial Arabic مولد mawlid, mevlid, mevlit, mulud among other vernacular pronunciations; sometimes ميلاد mīlād) is the observance of the birthday of Islamic prophet Muhammad which is commemorated in Rabi\' al-awwal, the third month in the Islamic calendar.',
                'start_date' => Carbon::parse('2024-05-01 00:00:00'),
                'end_date' => Carbon::parse('2024-05-01 00:00:00'),
                'type' => 'closed',
                'tags' => ['holiday', 'religious'],
            ],
            [
                'title' => 'Semester Final Exam',
                'body' => 'Semester Final Exam',
                'start_date' => Carbon::parse('2024-01-15 00:00:00'),
                'end_date' => Carbon::parse('2024-01-20 00:00:00'),
                'type' => 'half_closed',
                'tags' => ['exam', 'semester'],
            ],
            [
                'title' => 'Grade report card',
                'body' => 'Grade report card',
                'start_date' => Carbon::parse('2024-02-15 00:00:00'),
                'end_date' => Carbon::parse('2024-02-15 00:00:00'),
                'type' => 'closed',
                'tags' => ['report', 'card'],
            ],
            [
                'title' => 'Teachers workshop',
                'body' => 'Workshop are designed to help teachers improve their skills and knowledge. They can cover topics such as teaching methods, classroom management, and curriculum development.',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(9),
                'type' => 'half_closed',
                'tags' => ['training', 'workshop'],
            ],
            [
                'title' => 'Parent Teacher meeting',
                'body' => 'Workshop are designed to help teachers improve their skills and knowledge. They can cover topics such as teaching methods, classroom management, and curriculum development.',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(9),
                'type' => 'half_closed',
                'tags' => ['training', 'workshop'],
            ],
        ];
    }
}
