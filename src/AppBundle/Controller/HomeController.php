<?php

namespace AppBundle\Controller;

use AppBundle\Astrobin\Services\getTodayImage;
use AppBundle\Repository\MessierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class HomeController
 * @package AppBundle\Controller
 */
class HomeController extends Controller
{

    const OFFSET = 0;

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
        $messierRepository = $this->container->get('app.repository.messier');

        /** @var getTodayImage $imageOfTheDayWS */
//        $imageOfTheDayWS = $this->container->get('astrobin.webservice.gettodayimage');
//        dump($imageOfTheDayWS->getTodayImage());

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
}
