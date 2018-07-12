<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;


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
        /** @var Router $router */
        $router = $this->container->get('router');

        if (!is_null($language)) {
            $this->get('session')->set('_locale', $language);
        }

        // Previous URL
//        $url = $request->headers->get('referer');

//        if (empty($url)) {
//            $redirectUrl = $router->generate('homepage');
//        } else {
            $routeName = $request->get('_route');
            $routeParams = $request->get('_route_params');

            dump($routeName, $routeParams);

//        }
        die();
//        die($redirectUrl);

        return new RedirectResponse($redirectUrl);
    }


    /**
     * @Route("/{_locale}/contact",
     *  name="contact_me"
     * )
     * @return Response
     */
    public function contactAction(Request $request)
    {
        $params = [];

        /** @var RouterInterface $router */
        $router = $this->container->get('router');
        $options = [
            'method' => 'POST',
            'action' => $router->generate('contact_me')
        ];

        $form = $this->createForm(ContactFormType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

            }
        }

        $params['form'] = $form->createView();

        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->container->getParameter('http_ttl')
        );

        return $this->render(':pages:contact.html.twig', $params, $response);
    }
}
