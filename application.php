#!/usr/bin/env php

<?php

require __DIR__.'/vendor/autoload.php';

use App\Command\DroidControlCommand;
use App\Generator\DroidPathGenerator;
use GuzzleHttp\Client;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new DroidControlCommand(
    new Client(['base_uri' => 'https://deathstar.dev-tests.vp-ops.com/empire.php']),
    new DroidPathGenerator()
));

$application->run();