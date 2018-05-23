<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Messier;
use AppBundle\Repository\DsoRepository;
use AppBundle\Repository\SearchRepository;
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
        $searchTerms = '';
        /** @var SearchRepository $searchRepository */
        $searchRepository = $this->container->get('app.repository.search');
        if ($request->request->has('search')) {
            $searchTerms = $request->request->get('search');
        }

        $collectionMessier = DsoRepository::COLLECTION_NAME;

        $result[$collectionMessier] = $searchRepository->buildSearch($searchTerms, $collectionMessier);
        $data = [
            "status" => true,
            "error" => null,
            "data" => [
                'astronomy' => $result
            ]
        ];

        $jResponse = new JsonResponse();
        $jResponse->setData($data);

        return $jResponse;
    }
}
