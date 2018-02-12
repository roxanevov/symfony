<?php
/**
 * Created by PhpStorm.
 * User: rox
 * Date: 12/02/2018
 * Time: 08:58
 */

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class ShowRepository extends EntityRepository
{
    /**public function findAllByQuery($query){
        return $this->createQueryBuilder('s')
            ->where('LOWER(s.name)LIKE :query')

    }**/
}