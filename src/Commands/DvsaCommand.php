<?php

namespace FourthDimension\Dvsa\Commands;

use Illuminate\Console\Command;

class DvsaCommand extends Command
{
    public $signature = 'laravel-dvsa';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
