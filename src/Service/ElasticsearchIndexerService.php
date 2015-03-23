<?php

namespace Mcsaba\Eveonline\Service;

use Elasticsearch\Client;

class ElasticsearchIndexerService
{
    /** @var  Client */
    private $client;
    private $indexNamePrefix;

    public function __construct(Client $client, $indexNamePrefix)
    {
        $this->client = $client;
        $this->indexNamePrefix = $indexNamePrefix;
    }

    public function storeDocuments(array $documents, $type)
    {
        $this->createIndexIfNecessary();

        $params = array();
        $params['index'] = $this->getIndexName();
        $params['type']  = $type;

        foreach ($documents as $key => $data) {
            if (!isset($data['@timestamp'])) {
                $data['@timestamp'] = date('c');
            }

            $params['body'] = $data;
            $this->client->index($params);
        }
    }

    private function createIndexIfNecessary()
    {
        $params = array();
        $params['index'] = $this->getIndexName();

        $haveToCreate = false;
        try
        {
            $this->client->indices()->getSettings($params);
        }
        catch (\Exception $e)
        {
            $haveToCreate = true;
        }

        if (!$haveToCreate)
        {
            return;
        }

        $mapTypeMapping = array(
            '_source' => array(
                'enabled' => true
            ),
            'properties' => array(
                'solarsystem' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'region' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'constellation' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'jumps' => array(
                    'type' => 'long'
                ),
                'ship_kills' => array(
                    'type' => 'long'
                ),
                'faction_kills' => array(
                    'type' => 'long'
                ),
                'pod_kills' => array(
                    'type' => 'long'
                ),
                'security' => array(
                    'type' => 'float'
                ),
                'killboard_solarsystem' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'killboard_region' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'dotlan_region' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'dotlan_constellation' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
                'dotlan_solarsystem' => array(
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ),
            )
        );

        $params['body']['mappings']['map'] = $mapTypeMapping;
        $params['body']['settings']['number_of_shards']   = 2;
        $params['body']['settings']['number_of_replicas'] = 1;

        $this->client->indices()->create($params);
    }

    public function getIndexName()
    {
        return $this->indexNamePrefix . '-' . date('Y.m.d');
    }

}
