<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Constellation;
use AppBundle\Helper\GenerateUrlHelper;
use AppBundle\Kuzzle\KuzzleHelper;

/**
 * Class ConstellationRepository
 * @package AppBundle\Repository
 */
class ConstellationRepository extends AbstractKuzzleRepository
{

    protected $kuzzleHelper;
    protected $urlHelper;

    protected static $listOrder = [
        'title_asc' => ['id.raw' => 'asc'],
        'title_desc' => ['id.raw' => 'desc'],
        'order_asc' => ['data.order' => 'asc'],
        'order_desc' => ['data.order' => 'desc'],
    ];

    const SEARCH_SIZE = 15;
    const COLLECTION_NAME = 'constellations';

    /**
     * ConstellationRepository constructor.
     * @param KuzzleHelper $kuzzleHelper
     * @param GenerateUrlHelper $urlHelper
     */
    public function __construct(KuzzleHelper $kuzzleHelper, GenerateUrlHelper $urlHelper)
    {
        parent::__construct($kuzzleHelper);
        $this->urlHelper = $urlHelper;
    }


    /**
     * @param $id
     * @return Constellation
     */
    public function getObjectById($id)
    {
        $result = $this->findById($id);
        if (0 < $result->getTotal()) {
            return $this->buildEntityByDocument($result->getDocuments()[0]);
        }
    }


    /**
     * @param $hem
     * @param $from
     * @param $size
     * @return array
     */
    public function getListByLoc($hem, $from, $size)
    {
        $listConst = [];
        $result = $this->findBy('term', ['data.loc' => $hem], null, 'title_asc', $from, $size, null);
        if (0 < $result->getTotal()) {
            foreach ($result->getDocuments() as $document) {
                $constellation = $this->buildEntityByDocument($document);
                array_push($listConst, $constellation);
            }
        }

        return [$listConst, $result->getTotal()];
    }

    /**
     * @param $document
     * @return Constellation
     */
    public function buildEntityByDocument($document)
    {
        $kuzzleEntity = $this->getKuzzleEntity();
        /** @var Constellation $constellation */
        $constellation = new $kuzzleEntity;

        $constellation->setLocale($this->getLocale())->buildObject($document);
        $this->urlHelper->generateUrl($constellation);

        return $constellation;
    }

    /**
     * @return Constellation
     */
    public function getKuzzleEntity()
    {
        return 'AppBundle\Entity\Constellation';
    }
}