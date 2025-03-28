<?php

namespace Mydnic\Volet\Commands;

use Illuminate\Console\Command;

class VoletCommand extends Command
{
    public $signature = 'volet';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
