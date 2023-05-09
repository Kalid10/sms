<?php

namespace Database\Seeders\Simulation;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = $this->subjects();

        foreach ($subjects as $subject) {
            Subject::factory()->create($subject);
        }
    }

    protected function filterSubjectsByTag(string|array $tags, Collection $subjects): Collection
    {
        return $subjects->filter(function ($subject) use ($tags) {
            if (is_array($tags)) {
                return array_intersect($tags, $subject['tags']) === $tags;
            }

            return in_array($tags, $subject['tags']);
        });
    }

    protected function subjects(): array
    {
        return [
            [
                'full_name' => 'Mathematics',
                'short_name' => 'MATH',
                'category' => 'Mathematics',
                'tags' => ['Algebra', 'Geometry', 'Calculus', 'Statistics', 'All Levels'],
                'priority' => 4,
            ],
            [
                'full_name' => 'English Language',
                'short_name' => 'ENG',
                'category' => 'Language Arts',
                'tags' => ['Arts', 'Reading', 'Writing', 'Communication', 'Literature', 'All Levels'],
                'priority' => 4,
            ],
            [
                'full_name' => 'አማርኛ',
                'short_name' => 'አማ',
                'category' => 'Language Arts',
                'tags' => ['Arts', 'Reading', 'Writing', 'Communication', 'Literature', 'ቋንቋ', 'All Levels'],
                'priority' => 4,
            ],
            [
                'full_name' => 'Biology',
                'short_name' => 'BIO',
                'category' => 'Natural Sciences',
                'tags' => ['Cellular Biology', 'Genetics', 'Ecology', 'Anatomy', 'Middle', 'Preparatory', 'Natural'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Chemistry',
                'short_name' => 'CHEM',
                'category' => 'Natural Sciences',
                'tags' => ['Organic Chemistry', 'Inorganic Chemistry', 'Physical Chemistry', 'Biochemistry', 'Middle', 'Preparatory', 'Natural'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Physics',
                'short_name' => 'PHYS',
                'category' => 'Natural Sciences',
                'tags' => ['Classical Mechanics', 'Thermodynamics', 'Electromagnetism', 'Quantum Mechanics', 'Middle', 'Preparatory', 'Natural'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Social Studies',
                'short_name' => 'SOC',
                'category' => 'Social Sciences',
                'tags' => ['Geography', 'History', 'Government', 'Economics', 'Elementary'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Physical Education',
                'short_name' => 'PE',
                'category' => 'Health and Fitness',
                'tags' => ['Health and Fitness', 'Sports', 'Exercise', 'Physical Development', 'All Levels'],
                'priority' => 2,
            ],
            [
                'full_name' => 'Music',
                'short_name' => 'MUS',
                'category' => 'Arts',
                'tags' => ['Arts', 'Instrumental Music', 'Vocal Music', 'Music Theory', 'Music History', 'Kindergarten', 'Elementary'],
                'priority' => 1,
            ],
            [
                'full_name' => 'Art',
                'short_name' => 'ART',
                'category' => 'Arts',
                'tags' => ['Arts', 'Drawing', 'Painting', 'Sculpture', 'Art History', 'Kindergarten', 'Elementary'],
                'priority' => 1,
            ],
            [
                'full_name' => 'Drama',
                'short_name' => 'DRAM',
                'category' => 'Arts',
                'tags' => ['Arts', 'Theater', 'Acting', 'Stagecraft', 'Dramatic Literature', 'All Levels'],
                'priority' => 1,
            ],
            [
                'full_name' => 'History',
                'short_name' => 'HIST',
                'category' => 'Social Sciences',
                'tags' => ['World History', 'Historiography', 'Civics', 'Middle', 'Preparatory', 'Social'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Geography',
                'short_name' => 'GEOG',
                'category' => 'Social Sciences',
                'tags' => ['Physical Geography', 'Human Geography', 'Cartography', 'Middle', 'Preparatory', 'Social'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Economics',
                'short_name' => 'ECON',
                'category' => 'Social Sciences',
                'tags' => ['Microeconomics', 'Macroeconomics', 'Business Economics', 'Preparatory', 'Social'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Business Studies',
                'short_name' => 'BS',
                'category' => 'Social Sciences',
                'tags' => ['Business Management', 'Entrepreneurship', 'Accounting', 'Preparatory', 'Social'],
                'priority' => 3,
            ],
            [
                'full_name' => 'Technical Drawing',
                'short_name' => 'TD',
                'category' => 'Natural Sciences',
                'tags' => ['Engineering Drawing', 'Architectural Drawing', 'Mechanical Drawing', 'Preparatory', 'Natural'],
                'priority' => 2,
            ],
            [
                'full_name' => 'Computer Science',
                'short_name' => 'CS',
                'category' => 'Natural Sciences',
                'tags' => ['Computer Programming', 'Computer Hardware', 'Computer Software', 'Elementary', 'High School'],
                'priority' => 2,
            ],
            [
                'full_name' => 'Nap Time',
                'short_name' => 'NAP',
                'category' => 'Kindergartens',
                'tags' => ['Nap Time', 'Sleep', 'Rest', 'Kindergarten'],
                'priority' => 2,
            ],
        ];
    }
}
