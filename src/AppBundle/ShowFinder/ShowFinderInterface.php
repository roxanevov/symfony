<?php

namespace AppBundle\ShowFinder;


interface ShowFinderInterface
{
    /**
     * Returns an array of shows according to querry passed.
     * @param Strins $query the query typed by the user
     * @return array $results the results got from the implementation of the ShowFinder
     */
    public function findByName($query);

    /**
     * Return the name of the implementation of the ShowFinder
     * @return String $name
     */
    public function getName();
}