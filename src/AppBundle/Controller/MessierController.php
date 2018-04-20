<?php

namespace AppBundle\Controller;

use AppBundle\Astrobin\Services\getTodayImage;
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
     *
     */
    const OFFSET = 0;

    /**
     *
     */
    const LIMIT = 20;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $params = [];

        /** @var MessierRepository $messierRepository */
//        $messierRepository = $this->container->get('app.repository.messier');

        /** @var getTodayImage $imageOfTheDayWS */
        $imageOfTheDayWS = $this->container->get('astrobin.webservice.gettodayimage');
        dump($imageOfTheDayWS->callWs());

//        $params['messier_objects'] = $messierRepository->getList(self::OFFSET, self::LIMIT);

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge($this->container->getParameter('http_ttl'));
        $response->headers->set(
            'X-Messier-Id', []
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

        $messierTest = 'm31';

        $astrobinWs = $this->container->get('astrobin.webservice.getobject');
        $astroMessier = $astrobinWs->getOneImage($messierTest);

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
