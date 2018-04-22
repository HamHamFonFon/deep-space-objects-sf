<?php

namespace AppBundle\Controller;

use AppBundle\Astrobin\AstrobinInterface;
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

    /**
     * @Route("/messier/{objectId}", name="messier_full")
     * @param Request $request
     * @param string $objectId
     * @return Response
     */
    public function fullAction(Request $request, $objectId)
    {
        $params = [];

        /** @var AstrobinInterface $astrobinWs */
        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $astrobinImage = $astrobinWs->getOneImage($objectId);


        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');
        $messier = $messierRepository->getMessier($objectId);


        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge($this->container->getParameter('http_ttl'));
        $response->headers->set(
            'X-Messier-Id', [$objectId]
        );

        return $this->render('pages/messier.html.twig', $params, $response);
    }

}
