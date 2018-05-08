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
    public function buildQuery($typeQuery, $query = [], $filters = [], $qSort = [], $aggregates = [], $from = 0, $size = 20)
    {
        if (!in_array($typeQuery, ['term', 'match', 'match_all'])) {
            return null;
        }

        $finalQuery = $queryClauses = $filterClauses = [];
        if (isset($typeQuery) && 'match_all' == $typeQuery) {
            $finalQuery['query'] = [$typeQuery => (object)$query];

        } else {
            if (isset($query) && 0 < count($query)) {

                // 1 field
                if (1 == count($query)) {
                    $queryClauses[$typeQuery] = $query;
                } else {
                    /**
                     * 2 cases :
                     * field: [value1, value 2]
                     * => terms: [field: [$value1, $value 2]
                     *
                     * [field:value1, field2: value 2]
                     * => [term :[field:value], term: [field2: value2]...]
                     */
                    foreach ($query as $field => $value) {
                        if (is_array($value)) {
                            array_push($queryClauses, ['terms' => [$field=>$value]]);
                        } else {
                            $queryClauses[][$typeQuery] = [$field=>$value];
                        }
                    }
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

        dump(json_encode($finalQuery));
        return $finalQuery;
    }
}
