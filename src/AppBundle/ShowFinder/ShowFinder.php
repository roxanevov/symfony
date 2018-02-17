<?php


namespace AppBundle\ShowFinder;


class ShowFinder
{
    private $finders;

    public function searchByName($query){
        $tmp = [];


        foreach ($this->finders as $finder){
            $tmp = array_merge($tmp, $finder->findByName($query));
        }
        return $tmp;
    }

    public function addFinder(ShowFinderInterface $finder){
        $this->finders[] = $finder;
    }

}