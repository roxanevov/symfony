<?php

namespace AppBundle\ShowFinder;

use AppBundle\Entity\Show;
use GuzzleHttp\Client;
use Symfony\Component\Validator\Constraints\DateTime;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;
    private $apiKey;

    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function findByName($query){
        $shows = [];
        $resultsApi = $this->client->get('/?apikey='.$this->apiKey.'&s='.$query);

        $decodeResultsApi = \GuzzleHttp\json_decode($resultsApi->getBody(),true);

        foreach ($decodeResultsApi["Search"] as $result){

            $allInfoShowEncode = $this->client->get('/?apikey='.$this->apiKey.'&i='.$result['imdbID']);
            $allInfoShow = \GuzzleHttp\json_decode($allInfoShowEncode->getBody(),true);

            $dateTime = \DateTime::createFromFormat('j M Y', $allInfoShow['Released']);

            $object_show = new Show();
            $object_show -> setName($allInfoShow['Title']);
            $object_show -> setAbstract($allInfoShow['Plot']);
            $object_show -> setCountry($allInfoShow['Country']);
            $object_show -> setAuthor($allInfoShow['Director']);
            $object_show -> setReleasedDate($dateTime);
            $object_show -> setMainPicture($allInfoShow['Poster']);
            $object_show -> setCategory($allInfoShow['Genre']);

            array_push($shows,$object_show);

        }

        return $shows;
    }

    public function getName(){
        return 'IMDB API';
    }
}