<?php

declare(strict_types=1);

namespace Skimpy\CLI;

use Skimpy\Database\Populator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildDatabase extends Command
{
    private $populator;

    public function __construct(Populator $populator)
    {
        $this->populator = $populator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:build');
        $this->setDescription('Rebuild database with all data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Building database...');
        $this->populator->populate();
        $output->writeln('<fg=green>Done!</fg=green>');

        return Command::SUCCESS;
    }
}
