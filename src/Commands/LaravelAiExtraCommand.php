<?php

namespace Lenorix\LaravelAiExtra\Commands;

use Illuminate\Console\Command;

class LaravelAiExtraCommand extends Command
{
    public $signature = 'laravel-ai-extra';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
