<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Messier;
use AppBundle\Form\ListOrderFormType;
use AppBundle\Repository\MessierRepository;
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
class MessierController extends Controller
{

    /**
     * @Route("/messier/{objectId}", options={"expose"=true}, name="messier_full")
     * @param Request $request
     * @param string $objectId
     * @return Response
     */
    public function fullAction(Request $request, $objectId)
    {
        $params = [];
        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');
        /** @var Messier $messier */
        $params['messier'] = $messier = $messierRepository->getMessier($objectId);

        // Get Messiers objects from same type
        $params['messiers_type'] = $messierRepository->getMessiersByType($messier->getType(), $messier->getId(), 3, 1);

        // Get Messiers objects from same constellation
        $params['messiers_const'] = $messierRepository->getMessiersByConst(strtolower($messier->getConstId()), $messier->getId(), 3, 1);

        if (is_null($params['messier'])) {
            throw new NotFoundHttpException();
        }

        $listKuzzleId = array_map(function(Messier $messier) {
            return $messier->getKuzzleId();
        }, array_merge($params['messiers_type'], $params['messiers_const']));
        array_unshift($listKuzzleId, $messier->getKuzzleId());

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge($this->container->getParameter('http_ttl'));
        $response->setMaxAge($this->container->getParameter('http_ttl'));
//        For Varnish
//        if (array_key_exists('messier', $params) && isset($params['messier'])) {
//            $response->headers->set(
//                'X-Kuzzle-Id', $listKuzzleId
//            );
//        }
        return $this->render('pages/messier.html.twig', $params, $response);
    }


    /**
     * @Route("/messier", name="messier_list")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $params = $data = [];

        $from = 0;
        $size = 12;
        $page = $firstPage = 1;
        $sort = MessierRepository::DEFAULT_SORT;

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

        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');
        list($params['total'], $params['list']) = $messierRepository->getList($from, $size, $sort, 1);


        $lastPage = ceil($params['total']/$size);

        $params['pagination'] = [
            'first_page' => $firstPage,
            'last_page' => $lastPage,
            'current_page' => $page,
            'route' => 'messier_list',
            'paramsRoute' => $data
        ];

        $params['form'] = $formOrder->createView();

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render(':pages:list.html.twig', $params, $response);
    }
}
