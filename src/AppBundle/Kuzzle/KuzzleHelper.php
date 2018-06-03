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
     * @param string $typeSearch
     * @param $typeQuery
     * @param array $query
     * @param array $filters
     * @param array $qSort
     * @param array $aggregates
     * @param int $from
     * @param int $size
     * @return array|null
     */
    public function buildQuery($typeSearch = 'must', $typeQuery, $query = [], $filters = [], $qSort = [], $aggregates = [], $from = 0, $size = 20)
    {
        if (!in_array($typeSearch, ['must', 'should', 'should_not'])) {
            return null;
        }
        if (!in_array($typeQuery, ['term', 'match', 'match_all', 'prefix'])) {
            return null;
        }

        // Add query
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
                            $typeSearch => $queryClauses
                        ]
                    ];
                    $finalQuery['query'] = $queryClauses;
                }
            }
        }

        // Filters
        if (isset($filters) && 0 < count($filters)) {
            $filtersQuery = [];
            if ('AND' == key($filters)) {
                $filterAnd = [];
                foreach ($filters['AND'] as $type => $filter) {
                    foreach ($filter as $field=>$value) {
                        $filterAnd[][$type] = [$field=>$value];
                    }
                }
                $filtersQuery['bool']['must'] = $filterAnd;

            }  elseif ('OR' == key($filters)) {
                $filterOr = [];
//                foreach ($filters['OR'] as $type => $filter) {
//                    $field = key($filter);
//                    $value = $filter[$field];
//                    $FilterAndLoop[] = [$field => $value];
//                    $filterAnd[$type] = $FilterAndLoop;
//                }
                $filtersQuery['bool']['should'] = $filterOr;
            }

            if (0 < count($filtersQuery)) {
                $finalQuery['query']['bool']['filter'] = $filtersQuery;
            }
        }

        // Add sort
        if (isset($qSort) && 0 < count($qSort)) {
            foreach ($qSort as $field=>$type) {
                $fieldSort[$field] = [];
                if (in_array($type, self::LIST_ORDER)) {
                    array_push($fieldSort[$field], ['order' => $type]);
                }
            }
            $finalQuery['sort'] = $fieldSort;
        }

        // Add aggregates
        if (isset($aggregates) && 0 < count($aggregates)) {
            $aggregatesFields = $aggregateFilter = [];
            if (array_key_exists('aggregates', $aggregates)) {
                $aggregatesFields = $aggregates['aggregates'];
            }

            if (array_key_exists('filter', $aggregates)) {
                $aggFilter = [];
                // TODO : améliorer ?
                foreach ($aggregates['filter'] as $type => $tabFieldValues) {
                    $aggFilter[$type] = $tabFieldValues;
                }
                $aggregateFilter['filter'] = $aggFilter;
                // FIN TODO
            } elseif (array_key_exists('global', $aggregates)) {
                $aggregateFilter['global'] = new \stdClass();
            }

            $finalQuery['aggregations'] = [
                'allfacets' => [
                    'aggregations' => $aggregatesFields
                ]
            ];

            if (0 < count($aggregateFilter)) {
                $finalQuery['aggregations']['allfacets'] += $aggregateFilter;
            }
        }

        $finalQuery['from'] = $from;
        $finalQuery['size'] = $size;
        return $finalQuery;
    }
}
