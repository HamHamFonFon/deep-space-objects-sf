<?php

namespace AppBundle\Controller;

use AppBundle\Repository\MessierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $searchTerms = null;
        if ($request->query->has('search')) {
            $searchTerms = $request->query->get('search');
        }


        /** @var MessierRepository $messierRepository */
        $messierRepository = $this->container->get('app.repository.messier');

        $mock = [
            "astronomy" => [
                'messiers' => [
                    ['id' => 'm42', 'value' => 'Orion Nebula'],
                    ['id' => 'm31', 'value' => 'Andromeda'],
                    ['id' => 'm45', 'value' => 'Pleiades']
                ],
                'constellations' => [
                    ['id' => 'tau', 'value' => 'Taurus'],
                    ['id' => 'ori', 'value' => 'Orion'],
                    ['id' => 'uma', 'value' => 'Ursa Major'],
                    ['id' => 'vig', 'value' => 'Virgo']
                ]
            ]

        ];

        $data = [
            "status" => true,
            "error" => null,
            "data" => $mock
        ];

        $jResponse = new JsonResponse();
        $jResponse->setData($data);

        return $jResponse;
    }
}
