<?php

namespace AppBundle\Controller;

use AppBundle\Repository\MessierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Translator;

/**
 * Class SearchController
 * @package AppBundle\Controller
 */
class SearchController extends Controller
{

    /**
     * @Route("/_autocomplete",
     *     options={"expose"=true},
     *     name="search_autocomplete")
     * @param Request $request
     * @return JsonResponse $jResponse
     */
    public function searchAction(Request $request)
    {

        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        if ($request->query->has('search')) {
            $searchTerms = $request->query->get('search');
        }

        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');

        $mock = [
            ['id' => 'm42', 'value' => 'Nebula orion'],
            ['id' => 'm44', 'value' => 'Pleiades'],
            ['id' => 'm31', 'value' => 'Andromeda'],
        ];

        $jResponse = new JsonResponse();
        $jResponse->setData($mock);

        return $jResponse;
    }
}
