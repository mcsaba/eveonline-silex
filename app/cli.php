<?php

/*
 * Bootsrap for CLI application
 */

/** @var Application $app */
use Knp\Provider\ConsoleServiceProvider;
use Silex\Application;

$app = require __DIR__ . '/app.php';

$app->register(new ConsoleServiceProvider(), array(
    'application_name'          => 'cli',
    'console.name'              => 'EVE Online API aggregator CLI',
    'console.version'           => '1.0',
    'console.project_directory' => __DIR__
));

/* Load commands */
require __DIR__ . '/../resources/config/commands.php';

return $app['console'];
