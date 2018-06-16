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
        $constellation = null;
        $result = $this->findById($id);
        if (0 < $result->getTotal()) {
            dump($result->getDocuments()[0]);
            return $this->buildEntityByDocument($result->getDocuments()[0]);
        }
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