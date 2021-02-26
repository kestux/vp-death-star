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

### The design
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

That approach would allow me to avoid the spaghetti code in the `DroidControlCommand::execute` and would give possibility for a much nicer unit test coverage.

However, it would be a week...ish length task so after creating nice path generator with 100% code coverage I've decided to go against my beliefs and write spaghetti with the very bad unit coverage.

I hope the `DroidPathGenerator` will give you a pretty good idea of the way I'm coding and encouraging other to follow that.

### Integer array path returned from `DroidPathGenerator`

Initially I was doing the following:

```php
$this->direction--;
if (self::STEP_LEFT > $this->direction) {
    $this->direction = self::STEP_RIGHT;
}
```

So the `int` array from `-1` to `1` was very handy for the algorithm. 

However, the next morning I realised that such approach might lead into live-lock with droid stuck bouncing from right to left and back. So decided to rework all the algorithm by trying to reuse old tests as much as possible thus stayed with the `int` array approach.

### `Try/catch` for `Client::request`

I absolutely forgot that `GuzzleHttp` client throws `RequestException` on `4xx` responses. However, the big functional test for the command was written, and I didn't want to rewrite it. :)

### Dependency Injection

In the real world I would create a `services.yaml` (_Symfony_ config file), where I would define all the dependencies, but wanted to save some precious time on setting up the `Kernel` and the `Container`.