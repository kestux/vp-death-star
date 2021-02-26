# vp-death-star

## Kęstutis Kačinskas Technical Task
An application to guide the Empire sentry droids to reach and reinforce the vulnerable point before enemy X-wings will have a chance to attack it.

## Setup and run

 * Install: `composer install`
 * Simple run: `./application.php death-star.droid.control`
 * All tests: `vendor/bin/phpunit`
 * Info about CLI command: `./application.php death-star.droid.control -h`
 * Change number of total droids to send: `./application.php death-star.droid.control -s 10`

## Notes

### Design
Initial idea was to create something like
```php
$droidDispatcher = new App\Dispatcher\DroidDispatcher(
    new \App\Generator\DroidPathGenerator(),
    new \App\Client\DroidClient(new GuzzleHttp\Client())
);

$application = new Application();

$application->add(new \App\Command\DroidControlCommand($droidDispatcher));
```

Where 
 * The `DroidClient` would encapsulate converting from array to path string and response conde to string. I.e. the client wrapper would have acted as request serializer and response parser.
 * The `DroidDispatcher` would create new droid would pass it to the `DroidClient` and would return response to the command for additional checks. Something like the following:

```php
$status = $this->droidDispatcher
    ->initNewDroid()
    ->send()
    ->getStatus();

```

That approach would allow me to avoid that spaghetti code in the `DroidControlCommand::execute` and would give possibility for a much nicer unit test coverage.

However, it would be a week...ish length task so after creating nice path generator with 100% code coverage I've decided to go against my beliefs and write spaghetti with very bad unit coverage.

I hope the `DroidPathGenerator` will give you a pretty good view of the way I'm coding and encourage other to follow it.

### Array path returned form `DroidPathGenerator`

Initially I was doing 

```php
$this->direction--;
if (self::STEP_LEFT > $this->direction) {
    $this->direction = self::STEP_RIGHT;
}
```

However, the next morning I realised that such approach might lead into live-lock with droid stuck bouncing right-left. So decided to rework all the algorithm by trying to reuse old test as much as possible and stayed with the array approach.
