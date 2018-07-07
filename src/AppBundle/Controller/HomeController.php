<?php

namespace AppBundle\Controller;

use AppBundle\Form\HomeSearchFormType;
use AppBundle\Repository\DsoRepository;
use Astrobin\Response\Today;
use Astrobin\Services\GetTodayImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;


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
     * @return Response
     *
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function indexAction(Request $request)
    {
        $params = [];

        /** @var Router $router */
        $router = $this->container->get('router');

        /** @var GetTodayImage $astrobinWs */
        $astrobinWs = $this->container->get('astrobin.webservice.gettodayimage');
        /** @var Today $imageOfTheDay */
        for ($i = 1; $i <= 3; $i++) {
//            $params['images_day'][] = $astrobinWs->getDayImage($i);
        }

        // Form search
        $formOptions = [
            'method' => 'POST',
            'action' => $router->generate('homepage')
        ];
        $formSearch = $this->createForm(HomeSearchFormType::class, null, $formOptions);
        $params['form'] = $formSearch->createView();

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
