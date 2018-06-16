<?php

namespace AppBundle\Controller;

use AppBundle\Repository\ConstellationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ConstellationController
 * @package AppBundle\Controller
 */
class ConstellationController extends Controller
{

    /**
     * @Route(
     *     "/constellations/{hem}",
     *  name="constellations_list_hem",
     *  requirements={
     *      "hem"="north|south"
     *  },
     *  options={"expose"=true}
     * )
     * @param Request $request
     * @param $hem
     * @return Response
     */
    public function listAction(Request $request, $hem)
    {
        $params = [];


        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render('', $params, $response);
    }


    /**
     * @param $request
     * @param $id
     * @Route(
     *  "/constellation/{id}",
     *  name="constellation_full",
     *  options={"expose"=true},
     *  requirements={
     *     "id"="[A-Za-z]{3}"
     *  }
     * )
     *
     * @return Response
     */
    public function fullAction(Request $request, $id)
    {
        $params = [];

        /** @var ConstellationRepository $constRepository */
        $constRepository = $this->container->get('app.repository.constellation');
        $constellation = $constRepository->getObjectById($id);
        dump($constellation);
        $params['const'] = $constellation;

        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render(':pages:constellation.html.twig', $params, $response);
    }
}
