<?php

use Silex\Application;

/* Main boostrap for application, autoloading */
require_once __DIR__ . '/bootstrap.php';

$app = new Application();

/* Global configuration for application */
$app['debug'] = true;

/* Basic configuration for application */
$app['base.path']   = __DIR__ . '/..';
$app['cache.path']  = __DIR__ . '/../var/cache';
$app['log.path']    = __DIR__ . '/../var/log';

require_once __DIR__ . '/../resources/config/services.php';

return $app;
