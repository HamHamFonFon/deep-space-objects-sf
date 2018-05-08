<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Messier;
use AppBundle\Helper\GenerateUrlHelper;
use AppBundle\Kuzzle\KuzzleHelper;
use Astrobin\Services\GetImage;
use Kuzzle\Document;
use Kuzzle\Util\SearchResult;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MessierRepository
 * @package AppBundle\Repository
 */
class MessierRepository extends AbstractKuzzleRepository
{

    const COLLECTION_NAME = 'messiers';

    /** @var KuzzleHelper  */
    public $kuzzleHelper;

    /** @var GetImage */
    public $wsGetImage;

    /** @var GenerateUrlHelper */
    public $urlHelper;

    /**
     * MessierRepository constructor.
     * @param KuzzleHelper $kuzzleHelper
     * @param GetImage $wsGetImage
     * @param RouterInterface $router
     */
    public function __construct(KuzzleHelper $kuzzleHelper, GetImage $wsGetImage, GenerateUrlHelper $urlHelper)
    {
        parent::__construct($kuzzleHelper);
        $this->wsGetImage = $wsGetImage;
        $this->urlHelper = $urlHelper;
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
            $messier = $this->buildEntityByDocument($result->getDocuments()[0]);

        }

        return $messier;
    }


    /**
     * @param $constId
     * @param $excludedMessier
     * @return array
     */
    public function getMessiersByConst($constId, $excludedMessier)
    {
        $listMessiers = [];
        $results = $this->findBy('term', ['properties.const_id' => $constId], [], ['properties.magnitude' => 'ASC'],0,10);
        if (0 < $results->getTotal()) {
            foreach ($results->getDocuments() as $document) {
                $listMessiers[] = $this->buildEntityByDocument($document);
            }
        }
        $listMessiers = array_filter($listMessiers, function (Messier $messier) use ($excludedMessier) {
            return $messier->getId() !== $excludedMessier;
        });

        return $listMessiers;
    }

    /**
     * Get objects messiers by type, filtered by constellation
     *
     * @param $type
     * @param null $const
     * @param $sort
     * @return array
     */
    public function getMessiersByType($type)
    {
        $listMessiers = [];
        $results = $this->findBy('term', ['properties.type' => $type], [], [], 0 ,20);
        if (0 < $results->getTotal()) {
            foreach ($results->getDocuments() as $document) {
                $listMessiers[] = $this->buildEntityByDocument($document);
            }
        }

        return $listMessiers;
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
        $listItems = $this->findBy('match_all', [], [], ['messier_order' => 'asc'], $from, $size);
        if (!is_null($listItems) && 0 < $listItems->getTotal()) {
            foreach ($listItems->getDocuments() as $document) {
//                $class = $this->getKuzzleEntity();
                $listMessiers[] = new Messier($document);
            }
        }

        return $listMessiers;
    }


    /**
     * TODO : make a factory pattern
     *
     * @param Document $kuzzleDocument
     * @return Messier
     */
    private function buildEntityByDocument(Document $kuzzleDocument)
    {
        $kuzzleEntity = $this->getKuzzleEntity();
        /** @var Messier $messier */
        $messier = new $kuzzleEntity;

        $id = $kuzzleDocument->getId();
        $messier->buildObject($kuzzleDocument)->setId($id);
        $this->urlHelper->generateUrl($messier);

        try {
            $astrobinImage = $this->wsGetImage->getImagesBySubject($id, 6);
            $messier->addImages($astrobinImage);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

        return $messier;
    }

    /**
     * @return string
     */
    protected function getKuzzleEntity()
    {
        return '\AppBundle\Entity\Messier';
    }
}
