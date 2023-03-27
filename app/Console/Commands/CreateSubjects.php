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
            ['full_name' => 'Mathematics', 'short_name' => 'MATH'],
            ['full_name' => 'English Language', 'short_name' => 'ENG'],
            ['full_name' => 'Computer Science', 'short_name' => 'CSCI'],
            ['full_name' => 'Physics', 'short_name' => 'PHYS'],
            ['full_name' => 'Chemistry', 'short_name' => 'CHEM'],
        ]);

        $subjects->each(function ($subject) {
            Subject::create([
                'full_name' => $subject['full_name'],
                'short_name' => $subject['short_name'],
            ]);
        });

        $this->info('Subjects created successfully.');
    }
}
