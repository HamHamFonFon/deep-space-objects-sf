<?php

namespace AppBundle\Controller;

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
     * @Route("/messier/{objectId}", name="messier_full")
     * @param Request $request
     * @param string $objectId
     * @return Response
     */
    public function fullAction(Request $request, $objectId)
    {
        $params = [];

        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');
        $params['messier'] = $messierRepository->getMessier($objectId);

        if (is_null($params['messier'])) {
            throw new NotFoundHttpException();
        }
        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge($this->container->getParameter('http_ttl'));
        if (array_key_exists('messier', $params) && isset($params['messier'])) {
            $response->headers->set(
                'X-Kuzzle-Id', $params['messier']->getKuzzleId()
            );
        }

        return $this->render('pages/messier.html.twig', $params, $response);
    }


    /**
     * @Route("/messier", name="messier_list")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $params = [];

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render(':pages:list.html.twig', $params, $response);
    }
}
