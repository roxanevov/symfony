<?php

namespace AppBundle\ShowFinder;

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use GuzzleHttp\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\DateTime;

class OMDBShowFinder implements ShowFinderInterface
{
    private $client;
    private $tokenStorage;
    private $apiKey;

    public function __construct(Client $client, TokenStorage $tokenStorage, $apiKey)
    {
        $this->client = $client;
        $this->tokenStorage = $tokenStorage;
        $this->apiKey = $apiKey;
    }

    public function findByName($query){


        $resultsApi = $this->client->get('/?apikey='.$this->apiKey.'&t='.$query);
        $json = \GuzzleHttp\json_decode($resultsApi->getBody(),true);

        if ($json['Response']== 'False' && $json['Error'] == 'Movie not found!'){
            return [];
        }else {
            return $this->convertToShow($json);
        }



        /*
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

        return $shows;*/
    }

    /**
     * Create a private fnction that transform a OMDB JSON into a show and category
     * @param String $json
     * Shows[] $show
     */
    private function convertToShow($json){
        $category =new Category();
        $category ->setName($json['Genre']);

        $shows = [];
        $show = new Show();
            $show
                ->setName($json['Title'])
                ->setDataSource(Show::DATA_SOURCE_OMBD)
                ->setAbstract($json['Plot'])
                ->setCountry($json['Country'])
                ->setAuthor($this->tokenStorage->getToken())
                ->setReleasedDate(new \DateTime($json['Released']))
                ->setMainPicture($json['Poster'])
                ->setCategory($category);
        $shows [] = $show;
        return $shows;
    }

    public function getName(){
        return 'IMDB API';
    }
}