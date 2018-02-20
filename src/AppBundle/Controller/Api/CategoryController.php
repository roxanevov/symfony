<?php


namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\Annotation as JMS;

/**
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{
    /**
     * @Method({"GET"})
     * @Route("/category", name="list")
     */
    public function listeAction(SerializerInterface $serializer ){

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $data = $serializer->serialize($category,'json');
        return new Response($data, Response::HTTP_OK,['Content-type'=>'application\json']);


    }

    /**
     * @Method({"GET"})
     * @Route("/category/{id}", name="get")
     */
    public function getCategorieAction(Category $category, SerializerInterface $serializer){

        $data = $serializer->serialize($category,'json');
        return new Response($data, Response::HTTP_OK,['Content-type'=>'application\json']);

    }

}