<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DroidControlCommand extends Command
{
    protected static $defaultName = 'death-star.droid.control';

    public function __construct(string $name = null)
    {
        parent::__construct($name ?? self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Sentry droids to reach and reinforce the vulnerable point');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Retrieving coordinates...');
        $output->writeln(' Done!');
        $output->write('Guiding droid...');
        $output->writeln(' Done!');
        $output->writeln('Result: FAILED!');

        return 410;
    }
}