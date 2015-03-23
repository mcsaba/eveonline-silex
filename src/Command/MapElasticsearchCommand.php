<?php

namespace Mcsaba\Eveonline\Command;

use Knp\Command\Command;
use Mcsaba\Eveonline\Service\ElasticsearchIndexerService;
use Mcsaba\Eveonline\Service\EveonlineApiService;
use Mcsaba\Eveonline\Service\JumpsShipKillsService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MapElasticsearchCommand extends Command
{

    /**
     * Configure command line options
     */
    protected function configure()
    {
        $this
            ->setName('eveonline:map2es')
            ->setDescription('Download Eveonline Map API to Elasticsearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $output->writeln('<info>Downloading API data</info>');

        /** @var EveonlineApiService $eveapi */
        $eveapi = $app['eveonline_api'];
        /** @var ElasticsearchIndexerService $indexer */
        $indexer = $app['elasticsearch_indexer'];
        /** @var JumpsShipKillsService $jumpShipKills */
        $jumpShipKills = $app['jump_shipkills'];

        $serverStatus = $eveapi->getServerStatus();
        $serverStatusApiDatas[] = array(
            'server_open' => (int)($serverStatus['result']['serverOpen'] == 'True' ? 1 : 0),
            'online_players' => (int)$serverStatus['result']['onlinePlayers'],
        );

        $mapApiDatas = $jumpShipKills->getApiDatas();

        $output->writeln('<info>Store the map datas to ES</info>');

        $indexer->storeDocuments($serverStatusApiDatas, 'serverstatus');
        $indexer->storeDocuments($mapApiDatas, 'map');

        $output->writeln('Done');
    }

}
