<?php

use Mcsaba\Eveonline\Command\MapElasticsearchCommand;
use Mcsaba\Eveonline\Command\MarketMqCommand;
use Silex\Application;

/** @var Application $app */

$app['console']->add(new MapElasticsearchCommand());
$app['console']->add(new MarketMqCommand());
