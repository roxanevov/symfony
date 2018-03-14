<?php
namespace AppBundle\Controller\Api;



use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use AppBundle\Entity\Show;
use JMS\Serializer\DeserializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     * @SWG\Tag(name="shows")
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
     */
    public function getShowAction(Show $show, SerializerInterface $serializer){

        $serializationContext = SerializationContext::create();

        return $this->returnResponse($serializer->serialize($show,'json',$serializationContext->setGroups(['show'])), Response::HTTP_OK);

    }

    /**
     * @Method({"POST"})
     * @Route("/show", name="create")
     *
     *
     *
     */
    public function createShowAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){

        $newShow = json_decode($request->getContent(),1);

        $Show = new Show();
        $Show->parseJson($newShow, $this->getDoctrine()->getManager());

        $error = $validator->validate($Show, null, ['create']);

        if($error->count() == 0){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Show);
            $em->flush();

            return new Response('Show created', Response::HTTP_CREATED,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);
    }

    /**
     * @Method({"PUT"})
     * @Route("/show/{id}", name="update")
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
     * @Method({"DELETE"})
     * @Route("/deleteShow/{id}", name="deletUser")
     */
    Public function deletShowAction(Show $show){

        $em = $this->getDoctrine()->getManager();
        $em->remove($show);
        $em->flush();

        return new Response('Show delete', Response::HTTP_CREATED,['Content-type'=>'application\json']);

    }

}