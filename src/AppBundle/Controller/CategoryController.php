<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Category;
use AppBundle\Type\CategoryType;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends Controller
{
    /*public function listeCategoryAction(){
        $categorys = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();

        return $this->render('_includes/categories.html.twig',["categories"=>$categorys]);
    }*/
    /**
     * @Route ("/create", name="create")
     */
    public function createAction(Request $request){

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form-> isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success','You successfully');
            return $this->redirectToRoute('show_list');
        }

        return $this->render('category/create.html.twig', ['categoryForm' => $form->createView()]);
    }

}