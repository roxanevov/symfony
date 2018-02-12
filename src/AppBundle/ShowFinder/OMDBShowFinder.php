<?php

namespace AppBundle\ShowFinder;

use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findByName($query){
        $results = $this->client->get('/?apikey=205854a2&t="back to"');
        dump(\GuzzleHttp\json_decode($results->getBody(),true)); die;
    }

    public function getName(){
        return 'IMDB API';
    }
}