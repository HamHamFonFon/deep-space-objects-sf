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
    public $kuzzleService;
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
     * @param array $filter
     * @param array $sort
     * @param int $size
     * @param $from
     */
    public function buildQuery($filter = [], $sort = [], $size = 1000, $from)
    {
        $options = [
            'from' => $from,
            'size' => $size
        ];

        // TODO : remove
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

        return $filterList;
    }



}