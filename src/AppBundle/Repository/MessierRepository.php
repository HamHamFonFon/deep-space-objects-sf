<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 22:47
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Messier;
use AppBundle\Kuzzle\KuzzleHelper;
use Kuzzle\Util\SearchResult;

/**
 * Class MessierRepository
 * @package AppBundle\Repository
 */
class MessierRepository extends abstractKuzzleRepository
{

    const COLLECTION_NAME = 'messiers';

    /** @var KuzzleHelper  */
    public $kuzzleHelper;

    /**
     * MessierRepository constructor.
     * @param KuzzleHelper $kuzzleHelper
     */
    public function __construct(KuzzleHelper $kuzzleHelper)
    {
        parent::__construct($kuzzleHelper);
    }


    /**
     * Get Messier object in Kuzzle and return it
     * @param $id
     * @return Messier|null
     */
    public function getMessier($id)
    {
        $messier = null;
        if (false === strpos(strtolower($id), 'm',0)) {
            return null;
        }

        /** @var SearchResult $result */
        $result = $this->findById($id);
        if (0 < $result->getTotal()) {
            $messier = new Messier($result->getDocuments());
        }

        return $messier;
    }


    /**
     * Get objects messiers by type, filtered by constellation
     * @param $type
     * @param $filters
     * @param $sort
     */
    public function getMessiersByType($type, $const = null, $sort)
    {
        $results = $this->findBy(['properties.type' => $type], ['properties.const_id' => ucfirst($const)]);
    }

    /**
     * @param $start
     * @param $to
     * @return array
     */
    public function getList($from, $size)
    {
        $listMessiers = [];
        /** @var  $listItems */
        $listItems = $this->findBy($from, $size);
        if (!is_null($listItems) && 0 < $listItems->getTotal()) {
            foreach ($listItems->getDocuments() as $document) {
//                $class = $this->getKuzzleEntity();
                $listMessiers[] = new Messier($document);
            }
        }

        return $listMessiers;
    }


    /**
     * @return string
     */
    protected function getKuzzleEntity()
    {
        return '\AppBundle\Entity\Messier';
    }
}