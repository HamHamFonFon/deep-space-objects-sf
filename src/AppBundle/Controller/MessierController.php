<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class MessierController
 * @package AppBundle\Controller
 */
class MessierController extends Controller
{

    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $params = [];

        /** @var Response $response */
        $response = new Response();

        return $this->render('pages/homepage.html.twig', $params, $response);
    }


    /**
     * @Route("/messier/{objectId}")
     * @param $request
     * @param $objectId
     * @return Response
     */
    public function messierAction(Request $request, string $objectId)
    {
        $params = [];

        /** @var Response $response */
        $response = new Response();

        return $this->render(':pages:messier.html.twig', $params, $response);
    }
}
