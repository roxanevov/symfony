<?php


namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{
    /**
     *@Method({"GET"})
     *@Route("/category", name="list")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Returns all the categories",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Category::class)
     *     )
     * )
     *
     *@SWG\Tag(name="Get categories")
     */
    public function listeAction(SerializerInterface $serializer ){

        throw new \Exception('lol');

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $data = $serializer->serialize($category,'json');
        return new Response($data, Response::HTTP_OK,['Content-type'=>'application\json']);

    }

    /**
     *@Method({"GET"})
     *@Route("/category/{id}", name="get")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Return a category",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Category::class)
     *     )
     * )
     *
     *@SWG\Tag(name="Get category")
     */
    public function getCategorieAction(Category $category, SerializerInterface $serializer){

        $data = $serializer->serialize($category,'json');
        return new Response($data, Response::HTTP_OK,['Content-type'=>'application\json']);

    }

    /**
     * @Method({"POST"})
     * @Route("/category", name="create")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Create a category"
     * )
     *
     *@SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of the category"
     * )
     *
     *@SWG\Tag(name="Create category")
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){

        $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
        $error = $validator->validate($category);

        if($error->count() == 0){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return new Response('Category created', Response::HTTP_CREATED,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);

    }

    /**
     * @Method({"PUT"})
     * @Route("/category/{id}", name="update")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Update a category"
     * )
     *
     *@SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The name of the category"
     * )
     *
     *@SWG\Tag(name="Update category")
     */
    public function updateAction(Request $request, Category $category, SerializerInterface $serializer, ValidatorInterface $validator){

        $newCategory = $serializer->deserialize($request->getContent(), Category::class, 'json');
        $error = $validator->validate($newCategory);

        if($error->count() == 0){

            $category->update($newCategory);
            $this->getDoctrine()->getManager()->flush();

            return new Response('Category update', Response::HTTP_OK,['Content-type'=>'application\json']);
        }
        return new Response($serializer->serialize($error,'json'), Response::HTTP_BAD_REQUEST,['Content-type'=>'application\json']);
    }

    /**
     * @Method({"DELETE"})
     * @Route("/deleteCategory/{id}", name="deleteCategory")
     *
     *@SWG\Response(
     *     response=200,
     *     description="Delete a category"
     * )
     *
     *@SWG\Tag(name="Delete category")
     */
    Public function deletShowAction(Category $category){

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return new Response('Category delete', Response::HTTP_CREATED,['Content-type'=>'application\json']);

    }

}