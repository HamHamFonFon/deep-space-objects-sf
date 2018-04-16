<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 22:47
 */

namespace AppBundle\Repository;


use AppBundle\Kuzzle\KuzzleHelper;

/**
 * Class MessierRepository
 * @package AppBundle\Repository
 */
class MessierRepository
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
     */
    public function getList($start, $to)
    {
        /** @var  $listItems */
        $listItems = $this->kuzzleHelper->listKuzzleDocuments(self::COLLECTION_NAME, $start, $to, null);
        if (!is_null($listItems)) {
            foreach ($listItems as $document) {
                dump($document);
            }
        }
    }


    /**
     * @return string
     */
    protected function getEntityClassName()
    {
        return 'AppBundle\Entity\Messier';
    }

}