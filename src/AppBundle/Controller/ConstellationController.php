<?php

namespace AppBundle\Controller;

use AppBundle\Repository\ConstellationRepository;
use AppBundle\Repository\DsoRepository;
use AppBundle\Repository\SearchRepository;
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
     *     "/{_locale}/constellations/{hem}",
     *  name="constellations_list_hem",
     *  requirements={
     *      "hem"="zodiac|north|south"
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

        $from = SearchRepository::SEARCH_FROM;
        $size = ConstellationRepository::SEARCH_SIZE;
        $page = $firstPage = 1;
        $sort = DsoRepository::DEFAULT_SORT;

        if ($request->query->has('page')) {
            $page = $request->query->get('page');
            $from = ($page-1)*$size;
        }

        if ($request->query->has('sort')) {
            $sort = $request->query->get('sort');
        }

        /** @var ConstellationRepository $constRepository */
        $constRepository = $this->container->get('app.repository.constellation');

        list($params['list'], $params['total']) = $constRepository->getListByLoc($hem, $from, $size);
        $lastPage = ceil($params['total']/$size);

        $params['listOrder'] = $this->container->getParameter('list.const.order');

        $params['pagination'] = [
            'first_page' => $firstPage,
            'last_page' => $lastPage,
            'current_page' => $page,
            'route' => 'constellations_list_hem',
            'paramsRoute' => array_merge(['hem' => $hem], $request->query->all())
        ];

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render('pages/constellations.html.twig', $params, $response);
    }


    /**
     * @param $request
     * @param $id
     * @Route(
     *  "/{_locale}/constellation/{id}",
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
