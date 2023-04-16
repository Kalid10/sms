<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateBatchSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-batch-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate batch sessions for the week';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        \App\Helpers\GenerateBatchSessions::generate();
    }
}
