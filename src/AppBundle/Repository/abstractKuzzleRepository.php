<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 17/04/18
 * Time: 19:58
 */

namespace AppBundle\Repository;


use AppBundle\Kuzzle\KuzzleHelper;


/**
 * Class abstractKuzzleRepository
 * @package AppBundle\Repository
 */
abstract class abstractKuzzleRepository
{
    /** @var KuzzleHelper  */
    protected $kuzzleHelper;

    /** @var \Kuzzle\Kuzzle  */
    protected $kuzzleService;

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
     * @param $id
     */
    public function findById($id)
    {
        $kuzzleDocument = $this->kuzzleService->query([]);
    }


    /**
     * Find a list of documents
     *
     * @param $collection
     * @param $from
     * @param $to
     * @return \Kuzzle\Util\SearchResult
     */
    public function findAllByCollection($collection, $from, $to)
    {
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        $filter = [];
        $filters = $this->kuzzleHelper->buildQuery($filter, $from, $to);
        $searchResult = $kuzzleCollection->search($filters);

        return $searchResult;
    }


}