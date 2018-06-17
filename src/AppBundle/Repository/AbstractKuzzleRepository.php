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
use Kuzzle\Document;
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
     * List for sorting request
     * @return mixed
     */
    public static final function getListOrder()
    {
        return static::$listOrder;
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

        $listOrder = self::getListOrder();
        if (!in_array($sort, array_keys($listOrder))) {
            $qSort = $listOrder[self::DEFAULT_SORT];
        } else {
            $qSort = $listOrder[$sort];
        }

        /** @var SearchResult $searchResult */
        return $kuzzleCollection->search(
            $this->kuzzleHelper->buildQuery('must', $typeQuery, $query, $filters, $qSort, $aggregates, $from, $size)
        );
    }


    /**
     * Search an object by his name/title
     * @param $id
     * @return SearchResult
     */
    protected function findById($id)
    {
//        $field = 'id';
//        if (preg_match('/[-_+]+/', $id)) {
            $field = 'id.raw';
//        }
        return $this->findBy('term', [$field => $id], [], [],0, 1);
    }


    /**
     * Get Kuzzle Document from kuzzle identifier
     * @param $kuzzleDocumentId
     * @return Document|null
     */
    protected function getKuzzleDocument($kuzzleDocumentId)
    {
        /** @var abstractKuzzleDocumentEntity $kuzzleEntity */
        $kuzzleEntity = $this->getKuzzleEntity();
        $collection = $kuzzleEntity::getCollectionName();

        /** @var Collection $kuzzleCollection */
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        /** @var Document $document */
        $document = $kuzzleCollection->fetchDocument($kuzzleDocumentId);

        if ($document instanceof Document) {
            return $document;
        } else {
            return null;
        }
    }


    /**
     * @param Document $kuzzleDoc
     * @param $fields
     * @return Document
     */
    protected function updateDocument(Document $kuzzleDoc, $fields)
    {
        /** @var abstractKuzzleDocumentEntity $kuzzleEntity */
        $kuzzleEntity = $this->getKuzzleEntity();
        $collection = $kuzzleEntity::getCollectionName();

        /** @var Collection $kuzzleCollection */
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        return $kuzzleCollection->updateDocument($kuzzleDoc->getId(), $fields);
    }

    abstract protected function getKuzzleEntity();
}