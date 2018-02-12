<?php

namespace AppBundle\Controller;

use AppBundle\File\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Type\ShowType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Show;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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
        $form = $this->createForm(ShowType::class, $show, ['validation_groups'=>'create']);

        $form->handleRequest($request);

        if($form->isValid()){
            //dump($show);
            //die;
            $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());

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
    public function updateAction(Show $show, Request $request, FileUploader $fileUploader) {
        $isNewFile = false;

        $showForm = $this->createForm(ShowType::class, $show, ['validation_groups'=>['update']]);

        $showForm->handleRequest($request);

        if(!empty($show->getTmpPicture())){
            $isNewFile = true;
        }else {
            $show->setTmpPicture(new File($this->getParameter('kernel.project_dir').'/web'.$this->getParameter('upload_directory_file').'/'.$show->getMainPicture()));
        }

        if($showForm->isValid()){

            if ($isNewFile == true){
                $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());
                $show->setMainPicture($generatedFileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success','You successfully');
            return $this->redirectToRoute('show_list');
        }
        return $this->render('_includes/create.html.twig', ['showForm' => $showForm->createView(),'update'=>true]);

    }

    /**
     * @Route("/delete", name="delete")
     * @Method({"POST"})
     */
    public function deleteAction(Request $request, CsrfTokenManagerInterface $csrfTokenManager){

        $doctrine = $this->getDoctrine();
        $showId = $request->request->get('show_id');

        $show = $doctrine->getRepository('AppBundle:Show')->findOneById($showId);

        if(!$show){
            throw new NotFoundHttpException(sprintf('There is no show with the id %d',$showId));
        }

        $csrfToken = new CsrfToken('delete_show',$request->request->get('_csrf_token'));

        if($csrfTokenManager->isTokenValid($csrfToken)){
            $doctrine->getManager()->remove($show);
            $doctrine->getManager()->flush();

            $this->addFlash('success','The show have been successfully deleted');
        }else{
            $this->addFlash('danger','The csrf token is not valid. The deletion was not completed');
        }

        return $this->redirectToRoute('show_list');
    }

}