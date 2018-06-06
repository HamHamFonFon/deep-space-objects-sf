<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dso;
use AppBundle\Entity\Messier;
use AppBundle\Form\ListOrderFormType;
use AppBundle\Repository\DsoRepository;
use Astrobin\Services\GetImage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class MessierController
 * @package AppBundle\Controller
 */
class DsoController extends Controller
{

    /**
     * @route("/catalogue",
     *     name="catalog_search"
     * )
     * @param Request $request
     * @return Response
     */
    public function catalogAction(Request $request)
    {
        $params = [];



        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render('', $params, $response);
    }


    /**
     * @Route("/catalogue/{catalog}",
     *     options={"expose"=true},
     *     name="catalog_list",
     *     requirements={"catalog"="\w+"}
     * )
     * @param Request $request
     * @param $catalog
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function listAction(Request $request, $catalog)
    {
        $params = $data = $filters = [];

        $params['listOrder'] = $this->container->getParameter('list.messier.order');
        $params['catalog'] = $catalog;
        $from = 0;
        $size = 12;
        $page = $firstPage = 1;
        $sort = DsoRepository::DEFAULT_SORT;

        $reqQuery = $request->query->all();
        if ($request->query->has('page')) {
            $page = $request->query->get('page');
            $from = ($page-1)*$size;
        }

        if ($request->query->has('sort')) {
            $sort = $request->query->get('sort');
        }

        if ($request->query->has('mag')) {
            $range = $request->query->get('mag');
        }

        unset($reqQuery['page'], $reqQuery['sort'], $reqQuery['mag']);
        if (isset($reqQuery) && 0 < count($reqQuery)) {
            $type = 'term';
            $filters[$type] = call_user_func_array('array_merge', array_map(function($key, $value) {
                return ['data.' . $key=>$value];
            }, array_keys($reqQuery), $reqQuery));
        }

        if (isset($range) && !empty($range)) {
            $filters['range']['data.mag'] = $range;
        }

        /** @var DsoRepository $dsoRepository */
        $dsoRepository = $this->container->get('app.repository.dso');
        list($params['total'], $params['list'], $params['aggregates']) = $dsoRepository->getList($catalog, $filters, $from, $size, $sort, 1);
        unset($params['aggregates']['allfacets']['doc_count']);

        $lastPage = ceil($params['total']/$size);

        $data['catalog'] = $catalog;
        if (DsoRepository::DEFAULT_SORT !== $sort) {
            $data['order'] = $sort;
        }

        $params['pagination'] = [
            'first_page' => $firstPage,
            'last_page' => $lastPage,
            'current_page' => $page,
            'route' => 'catalog_list',
            'paramsRoute' => array_merge($data, $request->query->all())
        ];

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render(':pages:list.html.twig', $params, $response);
    }

    /**
     * @Route("/catalog/{catalog}/{objectId}",
     *     options={"expose"=true},
     *     name="dso_full",
     *     requirements={
     *         "catalog"="[a-zA-Z0-9-+_]+",
     *         "objectId"="[a-zA-Z0-9-+_]+"
     *     }
     * )
     * @param Request $request
     * @param $catalog
     * @param string $objectId
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function fullAction(Request $request, $catalog, $objectId)
    {
        $params = $listKuzzleId = [];
        /** @var DsoRepository $messierRepository */
        $dsoRepository = $this->container->get('app.repository.dso');
        /** @var Dso $dso */
        $params['dso'] = $dso = $dsoRepository->getObject(strtolower($objectId));
        if (is_null($params['dso'])) {
            throw new NotFoundHttpException();
        }

        // Get objects from same constellation
        if (!is_null($dso->getConstId())) {
            $params['dsos_const'] = $dsoRepository->getObjectsByConst(strtolower($dso->getConstId()), $dso->getId(), 3, 1);
            $listKuzzleId = array_map(function(Dso $dso) {
                return $dso->getKuzzleId();
            }, $params['dsos_const']);
        }

        array_unshift($listKuzzleId, $dso->getKuzzleId());

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge($this->container->getParameter('http_ttl'));
        $response->setMaxAge($this->container->getParameter('http_ttl'));
//        For Varnish
        $response->headers->set(
            'X-Kuzzle-Id', implode(' ', $listKuzzleId)
        );

        return $this->render('pages/dso.html.twig', $params, $response);
    }
}
