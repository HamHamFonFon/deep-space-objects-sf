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
     * @param $start
     * @param $to
     * @return array
     */
    public function getList($start, $to)
    {
        $listMessiers = [];
        /** @var  $listItems */
        $listItems = $this->findAllByCollection(self::COLLECTION_NAME, $start, $to);
        if (!is_null($listItems) && 0 < $listItems->getTotal()) {
            foreach ($listItems->getDocuments() as $document) {
//                $class = $this->getEntityClassName();
//                $listMessiers[] = new $class($document);
                $listMessiers[] = new Messier($document);
            }
        }

        return $listMessiers;
    }


    /**
     * @return string
     */
    protected function getEntityClassName()
    {
        return 'AppBundle\Entity\Messier';
    }

}