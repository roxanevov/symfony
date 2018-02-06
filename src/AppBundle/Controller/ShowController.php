<?php

namespace AppBundle\Controller;

use AppBundle\File\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Type\ShowType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Show;

/**
 * @Route(name = "show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name = "list")
     */
    public function listAction(){

        $shows = $this->getDoctrine()->getManager()->getRepository('AppBundle:Show')->findAll();

        return $this->render('show/list.html.twig', ['shows' => $shows]);
    }

    /**
     * @Route("/create", name = "create")
     */
    public function createAction(Request $request, FileUploader $fileUploader){

        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);

        $form->handleRequest($request);

        if($form->isValid()){

            $generatedFileName = $fileUploader->upload($show->getMainPicture(), $show->getCategory()->getName());

            $show->setMainPicture($generatedFileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'You successfully added a new show!');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('_includes/create.html.twig', ['showForm' => $form->createView()]);
    }
    public function categoriesAction(){

        return $this->render('_includes/categories.html.twig',[
            'categories' => ['web Design', 'HTML', 'Freebises', 'Javascript', 'CSS', 'Tutorials']
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function updateAction(Show $show, Request $request) {

        $showForm = $this->createForm(ShowType::class, $show, ['validation_groups'=>['update']]);
        $showForm->handleRequest($request);

        if($showForm->isValid()){
            dump($show);
            die;
            $this->addFlash('success','You successfully');
            return $this->redirectToRoute('show_list');
        }
        return $this->render('_includes/create.html.twig', ['showForm' => $showForm->createView()]);

    }

}