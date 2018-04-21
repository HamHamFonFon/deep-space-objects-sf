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

    const LIST_ORDER = ['asc', 'desc'];

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
     * Build a Elastic Search query
     * @param array $filters
     * @param array $qSort
     * @return array $finalQuery
     */
    public function buildQuery($query = [], $filters = [], $qSort = [], $aggregates = [], $from = 0, $size = 20)
    {

        $finalQuery = $queryClauses = $filterClauses = [];
        // Query Concatenate fields
        if (isset($query) and 0 < count($query)) {

            // Test if "match_all"
            if (1 == count($query) && 'match_all' == key($query)) {
                $finalQuery['query'] = $query;

            // Else; building query
            } else {
                foreach ($query as $field => $value) {
                    $term = is_array($value) ? 'terms' : 'term';
                    array_push($queryClauses, [
                        $term => [
                            $field => $value
                        ]
                    ]);
                }

                if (0 < count($queryClauses)) {
                    $queryClauses = [
                        'bool' => [
                            'must' => $queryClauses
                        ]
                    ];
                    $finalQuery['query'] = $queryClauses;
                }
            }
        }

        // Filters
        if (isset($filters) && 0 < count($filters)) {
            foreach ($filters as $field=>$value) {
                $term = is_array($filters) ? 'terms' : 'term';
                $filterClauses[] = [
                    $term => [
                        $field => $value
                    ]
                ];
            }

            if (0 < count($filterClauses)) {
                $filterClauses = [
                    'bool' => [
                        'must' => $filterClauses
                    ]
                ];

                $finalQuery = [
                    'filter' => $filterClauses
                ];
            }
        }

        // Add sort
        if (isset($sort) && 0 < count($sort)) {

            foreach ($qSort as $field=>$type) {
                $fieldSort = [];
                if (in_array($type, self::LIST_ORDER)) {
                    array_push($fieldSort, [$field => $type]);
                }
            }

            $finalQuery = [
                'sort' => [
                    'order'=> $fieldSort
                ]
            ];
        }

        // TODO Add agregates
        if (isset($aggregates) && 0 < count($aggregates)) {

        }

        $finalQuery['from'] = $from;
        $finalQuery['size'] = $size;

        return $finalQuery;
    }
}