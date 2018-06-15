<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Dso;
use AppBundle\Form\DsoFormType;
use AppBundle\Repository\DsoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;


/**
 * Class DsoController
 * @package AppBundle\Controller\Admin
 */
class DsoController extends Controller
{

    /**
     * @param Request $request
     * @param $kuzzleId
     *
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     * @Route("/catalog/dso/edit/{kuzzleId}",
     *  name="dso_edit"
     * )
     */
    public function editDsoAction(Request $request, $kuzzleId)
    {
        $params = [];

        /** @var DsoRepository $dsoRepository */
        $dsoRepository = $this->container->get('app.repository.dso');
        /** @var Router $router */
        $router = $this->container->get('router');

        /** @var Dso $dso */
        $dso = $dsoRepository->getObjectByKuzzleId($kuzzleId);

        $optionForm = [
            'action' => $router->generate("dso_edit", ["kuzzleId" => $dso->getKuzzleId()]),
            'method' => 'POST'
        ];

        $form = $this->createForm(DsoFormType::class, $dso, $optionForm);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $dataDso = $form->getData();

            $this->redirectToRoute('dso_full', ['catalog' => $dso->getCatalog(), 'objectId' => $dso->getId()]);
        }

        $params['form'] = $form->createView();

        /** @var Response $response */
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(
            $this->getParameter('http_ttl')
        );

        return $this->render('pages/admin/dso_edit.html.twig', $params, $response);
    }

}