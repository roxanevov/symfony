<?php


namespace AppBundle\Controller\Api;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{
    /**
     * @Method({"GET"})
     * @Route("/users", name="list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the users",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"user"})
     *     )
     * )
     *
     * @SWG\Tag(name="Get users")
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return a user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"user"})
     *     )
     * )
     *
     * @SWG\Tag(name="Get user")
     */
    public function getShowAction(User $user, SerializerInterface $serializer){

        $serializationContext = SerializationContext::create();
        return $this->returnResponse($serializer->serialize($user,'json',$serializationContext->setGroups(['user'])), Response::HTTP_OK);

    }

    /**
     * @Method({"POST"})
     * @Route("/user", name="create")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a user"
     * )
     *
     *@SWG\Parameter(
     *     name="fullname",
     *     in="query",
     *     type="string",
     *     description="The user's fullname"
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The user's email"
     * )
     *
     * @SWG\Tag(name="Create user")
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

    /**
     * @Method({"PUT"})
     * @Route("/user/{id}", name="update")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Update a user"
     * )
     *
     *@SWG\Parameter(
     *     name="fullname",
     *     in="query",
     *     type="string",
     *     description="The user's fullname"
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The user's email"
     * )
     *
     * @SWG\Tag(name="Update user")
     */
    public function updateAction(Request $request, User $user, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory){
        $newUser = $serializer->deserialize($request->getContent(), User::class, 'json');
        $error = $validator->validate($newUser);

        if($error->count() == 0){
            $encoder = $encoderFactory->getEncoder($newUser);
            $password =$encoder->encodePassword($newUser->getPassword(), null);

            $newUser->setPassword($password);
            $newUser->setRoles(explode(' ,',$newUser->getRoles()));

            $user->update($newUser);
            $this->getDoctrine()->getManager()->flush();

            return new Response('User update', Response::HTTP_OK,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);
    }

    /**
     * @Method({"DELETE"})
     * @Route("/deleteUser/{id}", name="deleteUser")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete a user"
     * )
     *
     * @SWG\Tag(name="Delete user")
     */
    Public function deletShowAction(User $user){

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new Response('User delete', Response::HTTP_CREATED,['Content-type'=>'application\json']);

    }

}