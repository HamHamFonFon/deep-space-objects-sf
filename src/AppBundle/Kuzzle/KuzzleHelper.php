<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 20:34
 */

namespace AppBundle\Kuzzle;

use Kuzzle\Collection;
use Kuzzle\Kuzzle;
use Kuzzle\Util\SearchResult;

/**
 * Class KuzzleHelper
 * @package AppBundle\Kuzzle
 */
class KuzzleHelper
{

    /** @var Kuzzle  */
    protected $kuzzleService;
    private $host;
    private $index;
    private $port;

    /**
     * KuzzleService constructor.
     * @param $host
     * @param $index
     */
    public function __construct($host, $index, $port)
    {
        $this->host = $host;
        $this->index = $index;
        $this->port = $port;

        /** @var Kuzzle kuzzleService */
        $this->kuzzleService = new Kuzzle($this->host, [
                'defaultIndex' => $this->index,
                'port' => $this->port
            ]
        );
    }


    /**
     * @param $collection
     * @param $start
     * @param $offset
     * @param $sort
     * @return \Kuzzle\Document[]|null
     */
    public function listKuzzleDocuments($collection, $start, $offset, $sort)
    {
        $listDocs = null;

        /** @var Collection $kuzzleCollection */
        $kuzzleCollection = $this->kuzzleService->collection($collection);

        $options = [
            'from' => $start,
            'size' => $offset
        ];

        $filterList = [
            'query' => [
                'match_all' => []
            ],
            'sort' => [
                'order' => [
                    'messier_order' => 'asc'
                ]
            ]
        ];

        /** @var SearchResult $result */
        $result = $kuzzleCollection->search($filterList, $options);
        if (0 < $result->getTotal()) {
            $listDocs = $result->getDocuments();
        }

        return $listDocs;
    }
}