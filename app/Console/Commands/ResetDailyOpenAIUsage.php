<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetDailyOpenAIUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-daily-open-ai-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command resets the daily OpenAI usage counter for every user.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Reset the daily usage counter for every user.
        User::query()->update(['openai_daily_usage' => 0]);
    }
}
