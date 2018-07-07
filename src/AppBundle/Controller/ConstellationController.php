<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dso;
use AppBundle\Repository\ConstellationRepository;
use AppBundle\Repository\DsoRepository;
use AppBundle\Repository\SearchRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


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
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     * @Route(
     *  "/{_locale}/constellation/{id}",
     *  name="constellation_full",
     *  options={"expose"=true},
     *  requirements={
     *     "id"="[A-Za-z]{3}"
     *  }
     * )
     *
     */
    public function fullAction(Request $request, $id)
    {
        $params = $listKuzzleId = [];


        /** @var ConstellationRepository $constRepository */
        $constRepository = $this->container->get('app.repository.constellation');
        $constellation = $constRepository->getObjectById($id);
        $params['const'] = $constellation;

        /** @var DsoRepository $dsoRepository */
        $dsoRepository = $this->container->get('app.repository.dso');
        $params['listDso'] = $dsoRepository->getObjectsByConst(strtolower($constellation->getId()), null, 20, 1);
        $listKuzzleId = array_map(function(Dso $dso) {
            return $dso->getKuzzleId();
        }, $params['listDso']);

        array_unshift($listKuzzleId, $constellation->getKuzzleId());

        /** @var Breadcrumbs $breadcrumbs */
        $breadcrumbs = $this->container->get('white_october_breadcrumbs');
        // Breadcrumbs
        $breadcrumbs->addItem('menu.homepage', $this->get('router')->generate('homepage'));
        $breadcrumbs->addItem('const_id', $this->get('router')->generate('constellations_list_hem', ['hem' => $constellation->getLoc()]));
        $breadcrumbs->addItem($constellation->getAlt(), $this->get('router')->generate('constellation_full', ['id' => $constellation->getId()]));

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        $response->headers->set(
            'X-Kuzzle-Id', implode(' ', $listKuzzleId)
        );

        return $this->render(':pages:constellation.html.twig', $params, $response);
    }
}
