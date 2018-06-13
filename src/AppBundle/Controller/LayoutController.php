<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;


/**
 * Class LayoutController
 * @package AppBundle\Controller
 */
class LayoutController extends Controller
{

    /**
     * Set language to locale
     * @Route("/_switchlang/{language}", options={"expose"=true}, name="switchlang")
     *
     * @source http://www.benjaminschied.fr/changer-la-langue-dans-symfony-2/ adapted to Symfony 3.4
     * @param Request $request
     * @param $language
     * @return RedirectResponse
     */
    public function switchLanguageAction(Request $request, $language)
    {
        if (!is_null($language)) {
            $this->get('session')->set('_locale', $language);
        }

        // Previous URL
        $url = $request->headers->get('referer');

        if (empty($url)) {
            /** @var Router $router */
            $router = $this->container->get('router');

            $url = $router->generate('homepage');
        }

        return new RedirectResponse($url);
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function breadcrumbsAction(Request $request)
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        $params['breadcrumb'][] = [
            'label' => 'menu.homepage',
            'full_url' => $router->generate('homepage')
        ];

        dump($request);

        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render('includes/layout/breadcrumbs.html.twig', $params, $response);
    }

}
