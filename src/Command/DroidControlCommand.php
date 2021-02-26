<?php

namespace App\Command;

use App\Generator\DroidPathGenerator;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DroidControlCommand extends Command
{
    protected static $defaultName = 'death-star.droid.control';

    private const DEFAULT_DROID_STOCK = 100;
    private const DEFAULT_SLEEP = 1;

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

    private int $sleep;
    private DroidPathGenerator $pathGenerator;
    private ClientInterface $vpClient;

    public function __construct(
        ClientInterface $vpClient,
        DroidPathGenerator $pathGenerator,
        int $sleep = self::DEFAULT_SLEEP,
        string $name = null
    ) {
        parent::__construct($name ?? self::$defaultName);

        $this->pathGenerator = $pathGenerator;
        $this->vpClient = $vpClient;
        $this->sleep = $sleep;
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
        $output->writeln('');

        $droidStock = (int) $input->getOption(self::OPTION_STOCK_NAME_LONG) ?? self::DEFAULT_DROID_STOCK;

        $output->writeln(sprintf('Sending up to %d droids...', $droidStock));

        $status = self::RESPONSE_STATUS_GONE;
        $i = 0;

        do {
            $i ++;

            if (!\array_key_exists($status, self::DISPATCHER_RESULT_TO_PATH_GENERATOR_STATUS_MAP)) {
                $output->writeln(\sprintf('Unknown result received: %d', $status));
            }

            $path = \implode('', \array_map(
                static fn (int $step): string => self::PATH_GENERATOR_TO_DISPATCHER_STEP_MAP[$step],
                $this->pathGenerator->getNewPath(self::DISPATCHER_RESULT_TO_PATH_GENERATOR_STATUS_MAP[$status])
            ));

            $output->write(\sprintf('%d. Droid with path "%s"... ', $i, $path));

            $qs = \http_build_query([
                'name' => 'Kestutis Kacinskas',
                'path' => $path
            ]);

            try {
                $status = $this->vpClient->request('GET', \sprintf('?%s', $qs))->getStatusCode();
            } catch (RequestException $ex) {
                $status = $ex->getResponse()->getStatusCode();
            }

            $output->writeln(\sprintf(
                '%s... ',
                \array_key_exists($status, self::DISPATCHER_RESULT_TO_PATH_GENERATOR_STATUS_MAP)
                    ? \strtoupper(self::DISPATCHER_RESULT_TO_PATH_GENERATOR_STATUS_MAP[$status])
                    : \sprintf('UNKNOWN RESULT: "%d"', $status)
            ));

            sleep($this->sleep);
        } while (self::RESPONSE_STATUS_OK !== $status && $i < $droidStock);

        return 0;
    }
}