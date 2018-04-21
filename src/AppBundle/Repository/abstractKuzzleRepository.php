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
     * @param $size
     * @return \Kuzzle\Util\SearchResult
     */
    protected function findBy($query, $filters, $sort, $from, $size, $aggregates = [])
    {
        /** @var abstractKuzzleDocumentEntity $kuzzleEntity */
        $kuzzleEntity = $this->getKuzzleEntity();
        $collection = $kuzzleEntity::getCollectionName();

        /** @var Collection $kuzzleCollection */
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        /** @var SearchResult $searchResult */
        dump(json_encode($this->kuzzleHelper->buildQuery($query, $filters, $sort, $aggregates, $from, $size))); die();
        $searchResult = $kuzzleCollection->search(
            $this->kuzzleHelper->buildQuery($query, $filters, $sort, $aggregates, $from, $size)
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
        return $this->findBy(['properties.name' => $id], [], [],0, 1);
    }


    abstract protected function getKuzzleEntity();
}