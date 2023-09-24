<?php

namespace spec\Skimpy\CLI;

use PhpSpec\ObjectBehavior;
use Skimpy\Database\Populator;
use PhpSpec\Exception\Example\SkippingException;
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

    function xit_destroys_and_rebuilds_the_database(
        InputInterface $input,
        OutputInterface $output,
        Populator $populator
    ) {
        throw new SkippingException(
            'Difficult mocking input interface here'
        );

        $input->isInteractive()->willReturn(false);
        $input->hasArgument('command')->willReturn(false);
        $output->writeln('Building database...')->shouldBeCalled();
        $populator->populate()->shouldBeCalled();
        $output->writeln('<fg=green>Done!</fg=green>')->shouldBeCalled();
        $this->run($input, $output);
    }
}
