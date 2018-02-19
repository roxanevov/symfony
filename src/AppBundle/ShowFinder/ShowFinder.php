<?php


namespace AppBundle\ShowFinder;


class ShowFinder
{
    private $finders;

    public function searchByName($query){
        $resluts = [];

        foreach ($this->finders as $finder){
            $resluts = array_merge($resluts, $finder->findByName($query));
        }
        return $resluts;
    }

    public function addFinder(ShowFinderInterface $finder){
        $this->finders[] = $finder;
    }

}