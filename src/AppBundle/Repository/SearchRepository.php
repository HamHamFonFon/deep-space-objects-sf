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
        'dso' => [
            'id',
            'data.desig',
            'data.alt.alt', // todo : add language
            'data.const_id',
            'data.type'
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
            $typeQuery = 'prefix';
            $query = $this->buildQuery($searchTerms, self::$listFields[$collection]);
            $searchResult = $kuzzleCollection->search(
                $this->kuzzleHelper->buildQuery($typeSearch, $typeQuery, $query, [], [], [], self::SEARCH_FROM, self::SEARCH_SIZE)
            );

            if (0 < $searchResult->getTotal()) {
                foreach ($searchResult->getDocuments() as $document) {
                    $documentContent = $document->getContent();

                    switch ($documentContent['catalog']) {
                        case 'messier':
                            if (!empty($documentContent['data']['alt']['alt'])) {
                                $label = $documentContent['data']['alt']['alt'] . ' - ' .  $documentContent['data']['desig'];
                            } else {
                                $label = ucfirst($documentContent['id']) . ' - ' . $documentContent['data']['desig'];
                            }
                            break;
                        case 'ngc':
                        case 'ic':
                            if (!empty($documentContent['data']['alt']['alt'])) {
                                $label = $documentContent['data']['alt']['alt'] . ' - ' .  $documentContent['data']['desig'];
                            } else {
                                $label = $documentContent['data']['desig'];
                            }
                            break;
                        default:
                            $label = $documentContent['data']['desig'];
                    }


                    $id = $documentContent['id'];
                    $type = $this->translator->trans('type.' . $documentContent['data']['type']);
                    $catalog = (!empty($documentContent['catalog'])) ? $this->translator->trans('catalog.' . $documentContent['catalog']) : '';

                    $result[] = [
                        'value' => $label,
                        'info' => $type,
                        'id' => $id,
                        'catalog' => $documentContent['catalog']
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
