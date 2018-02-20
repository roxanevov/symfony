<?php


namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{
    /**
     * @Method({"GET"})
     * @Route("/users", name="list")
     */
    public function listAction(SerializerInterface $serializer){

        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        $serializationContext = SerializationContext::create();

        $data = $serializer->serialize($users,'json', $serializationContext->setGroups(['user']));

        return $this->returnResponse($data,Response::HTTP_OK);

    }

    /**
     * @Method({"GET"})
     * @Route("/users/{id}", name="get")
     */
    public function getShowAction(User $user, SerializerInterface $serializer){

        $serializationContext = SerializationContext::create();
        return $this->returnResponse($serializer->serialize($user,'json',$serializationContext->setGroups(['user'])), Response::HTTP_OK);

    }

}