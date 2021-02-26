<?php

namespace App\Command;

use App\Generator\DroidPathGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DroidControlCommand extends Command
{
    protected static $defaultName = 'death-star.droid.control';

    private const DEFAULT_DROID_STOCK = 100;

    private const OPTION_STOCK_NAME_LONG = 'stock';
    private const OPTION_STOCK_NAME_SHORT = 's';

    private const RESPONSE_STATUS_EXPECTATION_FAILED = 417;
    private const RESPONSE_STATUS_GONE = 410;
    private const RESPONSE_STATUS_OK = 200;

    private const STEP_FORWARD = 'f';
    private const STEP_LEFT = 'l';
    private const STEP_RIGHT = 'r';

    private const DISPATCHER_RESULT_TO_PATH_GENERATOR_STATUS_MAP = [
        self::RESPONSE_STATUS_EXPECTATION_FAILED => DroidPathGenerator::RESULT_CRASHED,
        self::RESPONSE_STATUS_GONE => DroidPathGenerator::RESULT_LOST,
        self::RESPONSE_STATUS_OK => DroidPathGenerator::RESULT_SUCCESS,
    ];

    private const PATH_GENERATOR_TO_DISPATCHER_STEP_MAP = [
        DroidPathGenerator::STEP_FORWARD => self::STEP_FORWARD,
        DroidPathGenerator::STEP_LEFT => self::STEP_LEFT,
        DroidPathGenerator::STEP_RIGHT => self::STEP_RIGHT,
    ];

    public function __construct(string $name = null)
    {
        parent::__construct($name ?? self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Sentry droids to reach and reinforce the vulnerable point')
            ->addOption(
                self::OPTION_STOCK_NAME_LONG,
                self::OPTION_STOCK_NAME_SHORT,
                InputOption::VALUE_OPTIONAL,
                'The amount of droids in stock',
                self::DEFAULT_DROID_STOCK
            );
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