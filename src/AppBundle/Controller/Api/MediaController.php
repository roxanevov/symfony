<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Media;
use AppBundle\File\FileUploader;
use AppBundle\Type\MediaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/media", name="media_")
 */
class MediaController extends Controller
{
    /**
     * @Route("/")
     * @Method({"POST"})
     */
    public function uploadAction(Request $request, FileUploader $fileUploader, RouterInterface $router){


        $media = new Media();
        $media->setFile($request->files->get('file'));

        //validation media

        $generatedFileName = $fileUploader->upload($media->getFile(), time());

        $baseUrl = $router->getContext()->getScheme().'://'.$router->getContext()->getHost().':'.$router->getContext()->getHttpPort();
        $path = $this->getParameter('upload_directory_file').'/'.$generatedFileName;
        $media->setPath($baseUrl.$path);

        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();

        return $this->returnResponse($media->getPath(), Response::HTTP_CREATED);

    }

}