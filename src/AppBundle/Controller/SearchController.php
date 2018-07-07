<?php

namespace AppBundle\Controller;

use AppBundle\Repository\ConstellationRepository;
use AppBundle\Repository\DsoRepository;
use AppBundle\Repository\SearchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package AppBundle\Controller
 */
class SearchController extends Controller
{

    /**
     * @Route("/{_locale}/_autocomplete",
     *     options={"expose"=true},
     *     name="search_autocomplete")
     * @param Request $request
     * @return JsonResponse $jResponse
     */
    public function searchAction(Request $request)
    {
        $resultDso = $resultConst = [];

        $searchTerms = '';
        /** @var SearchRepository $searchRepository */
        $searchRepository = $this->container->get('app.repository.search');
        if ($request->request->has('search')) {
            $searchTerms = strtolower($request->request->get('search'));
        }

        // Search DSO
        $collectionDso = DsoRepository::COLLECTION_NAME;
        $resultDso = $searchRepository->buildSearch($searchTerms, $collectionDso);

        // Seach Constellation
        $collectionConst = ConstellationRepository::COLLECTION_NAME;
        $resultConst = $searchRepository->buildSearch($searchTerms, $collectionConst);

        if (array_key_exists('const_id', $resultDso) && !empty($resultConst)) {
            $resultConst[$collectionConst] = $resultConst[$collectionConst] + $resultDso['const_id'];
            unset($resultDso['const_id']);
        } elseif (array_key_exists('const_id', $resultDso) && empty($resultConst)) {
            $resultConst[$collectionConst] = $resultDso['const_id'];
            unset($resultDso['const_id']);
        }

        $data = [
            "status" => true,
            "error" => null,
            "data" => [
                'astronomy' => $resultDso+$resultConst
            ]
        ];

        $jResponse = new JsonResponse();
        $jResponse->setData($data);

        return $jResponse;
    }
}
