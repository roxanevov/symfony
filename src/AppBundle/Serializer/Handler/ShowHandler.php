<?php

namespace AppBundle\Serializer\Handler;

use AppBundle\Entity\Show;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonDeserializationVisitor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use JMS\Serializer\Handler\SubscribingHandlerInterface;

class ShowHandler implements SubscribingHandlerInterface
{

    private $doctrine;
    private $tokenStorage;

    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribingMethods()
    {
        return [
            [
                'event'=>GraphNavigator::DIRECTION_DESERIALIZATION,
                'method'=>'deserializer',
                'type'=>'AppBundle\Entity\Show',
                'format'=>'json'
            ],
        ];
    }

    public function deserializer(JsonDeserializationVisitor $visitor,  $data){

        $show = new Show();
        $show->setName($data['name']);
        $show->setAbstract($data['abstract']);
        $show->setCountry($data['country']);
        $show->setReleasedDate(new \DateTime($data['released_date']));
        $show->setMainPicture($data['main_picture']);

        $em = $this->doctrine->getManager();

        if(!$category = $em->getRepository('AppBundle:Category')->findOneById($data['category']['id'])){
            throw new \LogicException('Category does not exist');
        }

        $show->setCategory($category);

        $user = $this->tokenStorage->getToken()->getUser();
        $show->setAuthor($user);
        return $show;
        //dump($show);die;
    }
}