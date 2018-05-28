<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 17/04/18
 * Time: 19:58
 */

namespace AppBundle\Repository;


use AppBundle\Entity\abstractKuzzleDocumentEntity;
use AppBundle\Kuzzle\KuzzleHelper;
use Kuzzle\Collection;
use Kuzzle\Util\SearchResult;


/**
 * Class abstractKuzzleRepository
 * @package AppBundle\Repository
 */
abstract class AbstractKuzzleRepository
{
    /** @var KuzzleHelper  */
    protected $kuzzleHelper;

    /** @var \Kuzzle\Kuzzle  */
    protected $kuzzleService;

    protected $locale;

    const DEFAULT_SORT = 'order_asc';

    private static $listOrder = [
        'order_asc' => ['order' => 'asc'],
        'order_desc' => ['order' => 'desc'],
        'mag_asc' => ['data.mag' => 'asc'],
        'mag_desc' => ['data.mag' => 'desc']
    ];

    /**
     * abstractKuzzleRepository constructor.
     * @param KuzzleHelper $kuzzleHelper
     */
    public function __construct(KuzzleHelper $kuzzleHelper)
    {
        $this->kuzzleHelper = $kuzzleHelper;
        $this->kuzzleService = $kuzzleHelper->kuzzleService;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }


    /**
     * Find a list of documents
     *
     * @param $query
     * @param $filters
     * @param $sort
     * @param $from
     * @param $size
     * @param array $aggregates
     * @return \Kuzzle\Util\SearchResult
     */
    protected function findBy($typeQuery, $query, $filters, $sort, $from, $size, $aggregates = [])
    {
        /** @var abstractKuzzleDocumentEntity $kuzzleEntity */
        $kuzzleEntity = $this->getKuzzleEntity();
        $collection = $kuzzleEntity::getCollectionName();

        /** @var Collection $kuzzleCollection */
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        if (!in_array($sort, array_keys(self::$listOrder))) {
            $qSort = self::$listOrder[self::DEFAULT_SORT];
        }else {
            $qSort = self::$listOrder[$sort];
        }


        /** @var SearchResult $searchResult */
        $searchResult = $kuzzleCollection->search(
            $this->kuzzleHelper->buildQuery('must', $typeQuery, $query, $filters, $qSort, $aggregates, $from, $size)
        );

        return $searchResult;
    }


    /**
     * Search an object by his name/title
     * @param $id
     * @return SearchResult
     */
    protected function findById($id)
    {
        return $this->findBy('term', ['id' => $id], [], [],0, 1);
    }


    abstract protected function getKuzzleEntity();
}