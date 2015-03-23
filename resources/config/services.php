<?php

/** @var Application $app */

use Mcsaba\Eveonline\Service\ElasticsearchIndexerService;
use Mcsaba\Eveonline\Service\EveonlineApiService;
use Mcsaba\Eveonline\Service\JumpsShipKillsService;
use Mcsaba\Eveonline\Service\UniverseDataService;
use Silex\Application;

$app['elasticsearch_client'] = $app->share(function () {
    return new Elasticsearch\Client();
});

$app['universe_data'] = $app->share(function () use ($app) {
    return new UniverseDataService($app['resources.path']);
});

$app['eveonline_api'] = $app->share(function () use ($app) {
    return new EveonlineApiService($app['eveapi.keyID'], $app['eveapi.vCode'], $app['cache.path']);
});

$app['jump_shipkills'] = $app->share(function () use ($app) {
    return new JumpsShipKillsService($app['eveonline_api'], $app['universe_data']);
});

$app['elasticsearch_indexer'] = $app->share(function () use ($app) {
    return new ElasticsearchIndexerService($app['elasticsearch_client'], 'dev-mcsaba');
});
