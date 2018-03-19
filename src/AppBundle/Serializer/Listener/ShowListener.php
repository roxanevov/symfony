<?php

namespace AppBundle\Serializer\Listener;

use AppBundle\Entity\Show;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShowListener implements EventSubscriberInterface
{
    private $doctrine;
    private $tokenStorage;

    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event'=>Events::PRE_DESERIALIZE,
                'method'=>'preDeserializer',
                'class'=>'AppBundle\\Entity\\Show',
                'format'=>'json'
            ],
        ];
    }

    public function preDeserializer(PreDeserializeEvent $event){
        $data = $event->getData();
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

        //dump($show);die;
    }
}