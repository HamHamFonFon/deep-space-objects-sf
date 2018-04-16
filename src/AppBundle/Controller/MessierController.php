<?php

namespace AppBundle\Controller;

use AppBundle\Repository\MessierRepository;
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

    const OFFSET = 0;

    const LIMIT = 20;

    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $params = [];

        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');

        $params['messier_objects'] = $messierRepository->getList(self::OFFSET, self::LIMIT);

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->headers->set(
            $this->container->getParameter('http_ttl')
        );
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
