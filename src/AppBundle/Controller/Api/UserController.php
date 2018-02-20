<?php


namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @Method({"POST"})
     * @Route("/user", name="create")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory){
        $serializationContext = DeserializationContext::create();
        $user = $serializer->deserialize($request->getContent(), User::class, 'json',$serializationContext->setGroups(['user','user_create']));

        $error = $validator->validate($user);

        if($error->count() == 0){
            $encoder = $encoderFactory->getEncoder($user);
            $password =$encoder->encodePassword($user->getPassword(), null);

            $user->setPassword($password);
            $user->setRoles(explode(' ,',$user->getRoles()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new Response('User created', Response::HTTP_CREATED,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);

    }

}