<?php

namespace AppBundle\Repository;

use AppBundle\Kuzzle\KuzzleHelper;
use Kuzzle\Collection;
use Symfony\Component\Translation\Translator;

/**
 * Class SearchRepository
 * @package AppBundle\Repository
 */
class SearchRepository
{

    const SEARCH_FROM = 0;

    const SEARCH_SIZE = 20;

    /** @var KuzzleHelper  */
    protected $kuzzleHelper;

    /** @var \Kuzzle\Kuzzle  */
    protected $kuzzleService;

    /** @var Translator */
    protected $translator;

    public static $listFields = [
        'messiers' => [
            'messier_id',
            'properties.name',
            'properties.desig',
            'properties.alt',
            'properties.const_id'
        ]
    ];

    /**
     * SearchRepository constructor.
     * @param KuzzleHelper $kuzzleHelper
     */
    public function __construct(KuzzleHelper $kuzzleHelper, Translator $translator)
    {
        $this->kuzzleHelper = $kuzzleHelper;
        $this->kuzzleService = $kuzzleHelper->kuzzleService;
        $this->translator = $translator;
    }


    /**
     * @param $searchTerms
     * @param $collection
     * @return null
     */
    public function buildSearch($searchTerms, $collection)
    {
        $result = null;

        if (in_array($collection, array_keys(self::$listFields))) {

            /** @var Collection $kuzzleCollection */
            $kuzzleCollection = $this->kuzzleService->collection($collection);

            $typeSearch = 'should';
            $typeQuery = 'match';
            $query = $this->buildQuery($searchTerms, self::$listFields[$collection]);
            $searchResult = $kuzzleCollection->search(
                $this->kuzzleHelper->buildQuery($typeSearch, $typeQuery, $query, [], [], [], self::SEARCH_FROM, self::SEARCH_SIZE)
            );

            if (0 < $searchResult->getTotal()) {
                foreach ($searchResult->getDocuments() as $document) {
                    $documentContent = $document->getContent();
                    $label = (!empty($documentContent['properties']['alt'])) ? $this->translator->trans($document->getId().'.label') : $documentContent['properties']['alt'];
                    $result[] = [
                        'id' => $document->getId(),
                        'value' => (!empty($label)) ? implode(' - ', [$label,  strtoupper($document->getId())]) : $document->getId(),
                        'info' => $documentContent['properties']['desig']
                    ];
                }
            }
        }

        return $result;
    }


    /**
     * @param $searchTerm
     * @param $listField
     * @return array
     */
    private function buildQuery($searchTerm, $listFields)
    {
        return array_fill_keys($listFields, $searchTerm);
    }
}
