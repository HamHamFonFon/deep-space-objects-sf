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
class MessierRepository /** extends abstractKuzzleRepository */
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
        $this->kuzzleHelper = $kuzzleHelper;
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
        // TODO move request in abstractRepository
        $listItems = $this->kuzzleHelper->listKuzzleDocuments(self::COLLECTION_NAME, $start, $to, null);
        if (!is_null($listItems)) {
            foreach ($listItems as $document) {
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