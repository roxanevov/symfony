<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{
    /**
     * @Route("/list", name="list")
     */
    public function listeAction(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'non');

        return $this->render('user/list.html.twig', ['users'=> $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll()]);
    }
    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request, EncoderFactoryInterface $encoderFactory){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'non');

        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if($userForm->isValid()){
            $em = $this->getDoctrine()->getManager();

            $encoder = $encoderFactory->getEncoder($user);
            $hashedPassword = $encoder->encodePassword($user->getPassword(), null);

            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success','The user has been successfully');
            return $this->redirectToRoute('show_list');
        }
        return $this->render('user/create.html.twig', ['userForm'=>$userForm->createView()]);
    }

}