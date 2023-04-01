<?php

namespace App\Console\Commands;

use App\Models\Subject;
use Illuminate\Console\Command;

class CreateSubjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-subjects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Subjects';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $subjects = collect([
            ['full_name' => 'Mathematics', 'short_name' => 'MATH', 'category' => 'Mathematics', 'tags' => ['math', 'maths', 'all']],
            ['full_name' => 'English Literature', 'short_name' => 'ENG-LIT', 'category' => 'language', 'tags' => ['english', 'language', 'all']],
            ['full_name' => 'Biology', 'short_name' => 'BIO', 'category' => 'Science', 'tags' => ['science', 'middle school', 'high school', 'preparatory']],
            ['full_name' => 'English Language', 'short_name' => 'ENG', 'category' => 'language', 'tags' => ['english', 'language', 'all']],
            ['full_name' => 'Business Studies', 'short_name' => 'BUS', 'category' => 'Social Studies', 'tags' => ['social studies', 'preparatory']],
            ['full_name' => 'Social Studies', 'short_name' => 'SS', 'category' => 'Social Studies', 'tags' => ['social studies', 'primary school', 'middle school']],
            ['full_name' => 'History', 'short_name' => 'HIS', 'category' => 'Social Studies', 'tags' => ['social studies', 'middle school', 'high school', 'preparatory']],
            ['full_name' => 'Economics', 'short_name' => 'ECO', 'category' => 'Social Studies', 'tags' => ['social studies', 'middle school', 'high school', 'preparatory']],
            ['full_name' => 'Physics', 'short_name' => 'PHYS', 'category' => 'Science', 'tags' => ['science', 'middle school', 'high school', 'preparatory']],
            ['full_name' => 'Chemistry', 'short_name' => 'CHEM', 'category' => 'Science', 'tags' => ['science', 'middle school', 'high school', 'preparatory']],
        ]);

        $subjects->each(function ($subject) {
            Subject::create([
                'full_name' => $subject['full_name'],
                'short_name' => $subject['short_name'],
                'category' => $subject['category'],
                'tags' => $subject['tags'],
            ]);
        });

        $this->info('Subjects created successfully.');
    }
}