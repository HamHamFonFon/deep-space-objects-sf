<?php

namespace AppBundle\EventListener;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Templating\EngineInterface;

class ExceptionListener
{

    /** @var EngineInterface  */
    protected $templateEngine;

    /** @var Kernel */
    protected $kernel;

    /**
     * ExceptionListener constructor.
     * @param EngineInterface $templateEngine
     * @param $kernel
     */
    public function __construct(EngineInterface $templateEngine, $kernel)
    {
        $this->templateEngine = $templateEngine;
        $this->kernel = $kernel;
    }


    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ('prod' == $this->kernel->getEnvironment()) {
            $exception = $event->getException();

            /** @var Response $response */
            $response = new Response();
            $response->setContent(
                $this->templateEngine->render('exceptions/exceptions.html.twig', ['exception' => $exception])
            );

            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $event->setResponse($response);
        }
    }
}