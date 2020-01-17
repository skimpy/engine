<?php

declare(strict_types=1);

namespace Skimpy\Lumen\Console\Commands;

use Skimpy\Database\Populator;
use Illuminate\Console\Command;

class BuildDatabase extends Command
{
    protected $signature = 'skimpy:db:build';

    protected $description = 'Rebuild the skimpy DB with all data';

    public function handle(Populator $populator): void
    {
        $populator->populate();

        $this->info('success');
    }
}

