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
     * @Route("/catalogue/{catalog}",
     *     name="catalog_list",
     *     requirements={"catalog"="\w+"}
     * )
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request, $catalog)
    {
        $params = $data = [];

        $data['catalog'] = $params['catalog'] = $catalog;
        $from = 0;
        $size = 12;
        $page = $firstPage = 1;
        $sort = DsoRepository::DEFAULT_SORT;

        if ($request->query->has('page')) {
            $page = $request->query->get('page');
            $from = ($page-1)*$size;
        }

        if ($request->query->has('order')) {
            $sort = $request->query->get('order');
        }

        $optionsForm = [
            'method' => 'GET',
            'selectedOrder' => $sort
        ];
        $formOrder = $this->createForm(ListOrderFormType::class, null, $optionsForm);
        $formOrder->handleRequest($request);
        if ($formOrder->isValid() && $formOrder->isSubmitted()) {
            $data = $formOrder->getData();
            $sort = $data['order'];
        }

        /** @var DsoRepository $messierRepository */
        $dsoRepository = $this->container->get('app.repository.dso');
        list($params['total'], $params['list']) = $dsoRepository->getList($catalog, $from, $size, $sort, 1);

        $lastPage = ceil($params['total']/$size);

        $params['pagination'] = [
            'first_page' => $firstPage,
            'last_page' => $lastPage,
            'current_page' => $page,
            'route' => 'catalog_list',
            'paramsRoute' => $data
        ];

        $params['form'] = $formOrder->createView();
        $params['catalog'] = $catalog;

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
     *         "catalog"="\w+",
     *         "objectId"="[a-zA-Z0-9-+_]+"
     *     }
     * )
     * @param Request $request
     * @param string $objectId
     * @return Response
     */
    public function fullAction(Request $request, $catalog, $objectId)
    {
        $params = [];
        /** @var DsoRepository $messierRepository */
        $dsoRepository = $this->container->get('app.repository.dso');
        /** @var Dso $dso */
        $params['dso'] = $dso = $dsoRepository->getObject(strtolower($objectId));
        // Get bjects from same constellation
        $params['dsos_const'] = $dsoRepository->getObjectsByConst(strtolower($dso->getConstId()), $dso->getId(), 3, 1);

        if (is_null($params['dso'])) {
            throw new NotFoundHttpException();
        }

        $listKuzzleId = array_map(function(Dso $dso) {
            return $dso->getKuzzleId();
        }, $params['dsos_const']);
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
