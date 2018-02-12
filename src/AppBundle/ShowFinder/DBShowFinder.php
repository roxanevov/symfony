<?php

namespace AppBundle\ShowFinder;
use AppBundle\AppBundle;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DBShowFinder implements ShowFinderInterface
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function findByName($query){
        $shows = $this->doctrine->getRepository('AppBundle:Show')->findAllByQuery($query);
        return $shows;
    }

    public function getName(){
        return 'Local Database';
    }
}