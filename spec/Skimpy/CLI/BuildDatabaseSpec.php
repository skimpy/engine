<?php

namespace spec\Skimpy\CLI;

use PhpSpec\ObjectBehavior;
use Skimpy\Database\Populator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildDatabaseSpec extends ObjectBehavior
{
    function let(Populator $populator)
    {
        $this->beConstructedWith($populator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\CLI\BuildDatabase');
    }

    function it_destroys_and_rebuilds_the_database(
        InputInterface $input,
        OutputInterface $output,
        Populator $populator
    ) {
        $output->writeln('Building database...')->shouldBeCalled();
        $populator->populate()->shouldBeCalled();
        $output->writeln('<fg=green>Done!</fg=green>')->shouldBeCalled();
        $this->run($input, $output);
    }
}
