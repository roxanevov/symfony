<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/exemples")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/lol/pouet")
     * @Route("/{username}",
     *     requirements = {"username" = ".*"},
     *     schemes = {"http", "https"},
     *     name="homepage"
     * )
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $username ='')
    {
        return new Response(
            $this->renderView('default/index.html.twig', [
                'myVar' => $username
            ]),
            Response::HTTP_NOT_FOUND
        );
    }
}
