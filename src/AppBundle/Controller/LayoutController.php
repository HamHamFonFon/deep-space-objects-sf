<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;


/**
 * Class LayoutController
 * @package AppBundle\Controller
 */
class LayoutController extends Controller
{

    /**
     * Set language to locale
     * @Route("/setlocale/{language}", name="setlocale")
     *
     * @param Request $request
     * @param $language
     * @return RedirectResponse
     */
    public function switchLanguageAction(Request $request, $language)
    {

        /**
         * Source :
         * http://www.benjaminschied.fr/changer-la-langue-dans-symfony-2/ adapted to Symfony 3.4
         */

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

}
