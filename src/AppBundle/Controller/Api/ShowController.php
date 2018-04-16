<?php
namespace AppBundle\Controller\Api;



use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use AppBundle\Entity\Show;
use JMS\Serializer\DeserializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{
    /**
     * @Method({"GET"})
     * @Route("/shows", name="list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the shows",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Show::class, groups={"show"})
     *     )
     * )
     *
     * @SWG\Tag(name="Get shows")
     *
     */
    public function listeAction(SerializerInterface $serializer){

        $shows = $this->getDoctrine()->getRepository('AppBundle:Show')->findAll();

        $serializationContext = SerializationContext::create();

        $data = $serializer->serialize($shows,'json',$serializationContext->setGroups(['show']));
        return $this->returnResponse($data, Response::HTTP_OK);
    }

    /**
     * @Method({"GET"})
     * @Route("/shows/{id}", name="get")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return a show",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Show::class, groups={"show"})
     *     )
     * )
     *
     * @SWG\Tag(name="Get show")
     */
    public function getShowAction(Show $show, SerializerInterface $serializer){

        $serializationContext = SerializationContext::create();

        return $this->returnResponse($serializer->serialize($show,'json',$serializationContext->setGroups(['show'])), Response::HTTP_OK);

    }

    /**
     *@Method({"POST"})
     *@Route("/show", name="create")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Create a show"
     * )
     *
     *@SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of the show"
     * )
     *
     * @SWG\Parameter(
     *     name="abstract",
     *     in="query",
     *     type="string",
     *     description="The abstract of the show"
     * )
     *
     * @SWG\Parameter(
     *     name="category_id",
     *     in="query",
     *     type="integer",
     *     description="The id of the show's cartegory"
     * )
     *
     * @SWG\Parameter(
     *     name="released_date",
     *     in="query",
     *     type="integer",
     *     description="The show relaesed date"
     * )
     *
     * @SWG\Parameter(
     *     name="country",
     *     in="query",
     *     type="string",
     *     description="The show's country code (like france : 'FR' ) "
     * )
     *
     * @SWG\Parameter(
     *     name="user_id",
     *     in="query",
     *     type="integer",
     *     description="The id of the user"
     * )
     *
     *  @SWG\Parameter(
     *     name="main_picture",
     *     in="query",
     *     type="string",
     *     description="The url of show's main picture"
     * )
     *
     * @SWG\Tag(name="Creat show")
     */
    public function createShowAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){

        $newShow = json_decode($request->getContent(),1);

        $Show = new Show();
        $Show->parseJson($newShow, $this->getDoctrine()->getManager());

        $error = $validator->validate($Show, null, ['API']);

        if($error->count() == 0){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Show);
            $em->flush();

            return new Response('Show created', Response::HTTP_CREATED,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);
    }

    /**
     *@Method({"POST"})
     *@Route("/shows", name="create")
     */
    public function createShowListenerAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){

        $serializationContext = DeserializationContext::create();

        $newShow = $serializer->deserialize($request->getContent(), Show::class, 'json', $serializationContext->setGroups(['create']));

        $error = $validator->validate($newShow, null, ['API']);

        if($error->count() == 0){

            $em = $this->getDoctrine()->getManager();
            $em->persist($newShow);
            $em->flush();

            return new Response('Show created', Response::HTTP_CREATED,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);
    }

    /**
     *@Method({"PUT"})
     *@Route("/show/{id}", name="update")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Update a show"
     * )
     *
     *@SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of the show"
     * )
     *
     * @SWG\Parameter(
     *     name="abstract",
     *     in="query",
     *     type="string",
     *     description="The abstract of the show"
     * )
     *
     * @SWG\Parameter(
     *     name="category_id",
     *     in="query",
     *     type="integer",
     *     description="The id of the show's cartegory"
     * )
     *
     * @SWG\Parameter(
     *     name="released_date",
     *     in="query",
     *     type="integer",
     *     description="The show relaesed date"
     * )
     *
     * @SWG\Parameter(
     *     name="country",
     *     in="query",
     *     type="string",
     *     description="The show's country code (like france : 'FR' ) "
     * )
     *
     * @SWG\Parameter(
     *     name="user_id",
     *     in="query",
     *     type="integer",
     *     description="The id of the user"
     * )
     *
     *  @SWG\Parameter(
     *     name="main_picture",
     *     in="query",
     *     type="string",
     *     description="The url of show's main picture"
     * )
     *
     * @SWG\Tag(name="Update show")
     */
    public function updateShowAction(Request $request, Show $show, SerializerInterface $serializer, ValidatorInterface $validator){

        $newShow = json_decode($request->getContent(),1);
        $show->parseJson($newShow, $this->getDoctrine()->getManager());

        $error = $validator->validate($show, null, ['update']);

        if($error->count() == 0){

            $this->getDoctrine()->getManager()->flush();

            return new Response('Show update', Response::HTTP_CREATED,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);


    }

    /**
     *@Method({"DELETE"})
     *@Route("/deleteShow/{id}", name="deletUser")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Delete a show"
     * )
     *
     *@SWG\Tag(name="Delete show")
     */
    Public function deletShowAction(Show $show){

        $em = $this->getDoctrine()->getManager();
        $em->remove($show);
        $em->flush();

        return new Response('Show delete', Response::HTTP_CREATED,['Content-type'=>'application\json']);

    }

}