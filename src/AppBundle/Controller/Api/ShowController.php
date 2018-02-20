<?php
namespace AppBundle\Controller\Api;


use AppBundle\Entity\Show;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{
    /**
     * @Method({"GET"})
     * @Route("/shows", name="list")
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

}