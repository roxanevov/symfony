<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionListener implements EventSubscriberInterface
{
    const EXCEPTION_CODE = 'the server has a big problem';
    public static function getSubscribedEvents(){
        return [
            KernelEvents::EXCEPTION => ['processExceptionForApi', 1]
        ];
    }

    public function processExceptionForApi(GetResponseForExceptionEvent $event){
        // TODO Get the request and if it begins with /API => faire ce qui suit

        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        $api = substr($routeName, 0,3);
        if($api !== 'api'){
            return;
        }


        $data = [
            'code' => self::EXCEPTION_CODE,
            'message' => $event->getException()->getMessage()
        ];

        $response = new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        $event->setResponse($response);

    }
}