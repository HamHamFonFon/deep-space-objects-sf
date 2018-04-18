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
     * Find a list of documents
     *
     * @param $collection
     * @param $from
     * @param $to
     * @return \Kuzzle\Util\SearchResult
     */
    public function findBy($from, $size)
    {
        /** @var abstractKuzzleDocumentEntity $kuzzleEntity */
        $kuzzleEntity = $this->getKuzzleEntity();
        $collection = $kuzzleEntity::getCollectionName();

        dump($collection);

        /** @var Collection $kuzzleCollection */
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        // Build Query
        $filter = [];
        $sort = [];
        $options = [
            'from' => $from,
            'size' => $size
        ];
        $filters = $this->kuzzleHelper->buildQuery($filter, $sort);

        /** @var SearchResult $searchResult */
        $searchResult = $kuzzleCollection->search($filters, $options);

        return $searchResult;
    }

    /**
     * @param $id
     */
    public function findById($id)
    {
    }

    public function findOneBy()
    {

    }

    abstract protected function getKuzzleEntity();
}