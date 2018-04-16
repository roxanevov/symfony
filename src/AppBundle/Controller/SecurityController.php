<?php
/**
 * Created by PhpStorm.
 * User: rox
 * Date: 19/02/2018
 * Time: 11:57
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route(name = "security_")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name = "login")
     */
    public function loginAction(AuthenticationUtils $authUtils){

        // test overide error
        //throw new \Exception('test');

        return $this->render('security/login.html.twig',[
            'error' => $authUtils->getLastAuthenticationError(),
            'lastUsername'=> $authUtils->getLastUsername()
        ]);
    }
    /**
     * @Route("/login_check", name = "login_check")
     */
    public function loginCheckAction(){
        dump('this code is never executed');
    }

    /**
     * @Route("/logout", name = "logout")
     */
    public function logoutAction(){

    }

}