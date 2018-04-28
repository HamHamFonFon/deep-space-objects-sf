<?php

namespace AppBundle\Controller;

use AppBundle\Repository\MessierRepository;
use Astrobin\Services\GetImage;
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
     * @Route("/messier/{objectId}", name="messier_full")
     * @param Request $request
     * @param string $objectId
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function fullAction(Request $request, $objectId)
    {
        $params = [];

        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');
        $params['messier'] = $messierRepository->getMessier($objectId);

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge($this->container->getParameter('http_ttl'));
        $response->headers->set(
            'X-Kuzzle-Id', $params['messier']->getKuzzleId()
        );

        return $this->render('pages/messier.html.twig', $params, $response);
    }

}
