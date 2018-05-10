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
     * @param GenerateUrlHelper $urlHelper
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
            $messier = $this->buildEntityByDocument($result->getDocuments()[0], 6);

        }

        return $messier;
    }


    /**
     * @param $constId
     * @param $excludedMessier
     * @param $limit
     * @param $limitImages
     * @return array
     */
    public function getMessiersByConst($constId, $excludedMessier, $limit = 10, $limitImages = 1)
    {
        $listMessiers = [];
        $results = $this->findBy('term', ['properties.const_id' => $constId], [], ['properties.magnitude' => 'ASC'],0, $limit);
        if (0 < $results->getTotal()) {
            foreach ($results->getDocuments() as $document) {
                $listMessiers[] = $this->buildEntityByDocument($document, $limitImages);
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
     * @param $limit
     * @param $limitImages
     * @return array
     */
    public function getMessiersByType($type, $excludedMessier, $limit = 10, $limitImages = 1)
    {
        $listMessiers = [];
        $results = $this->findBy('term', ['properties.type' => $type], [], [], 0 ,$limit);
        if (0 < $results->getTotal()) {
            foreach ($results->getDocuments() as $document) {
                $listMessiers[] = $this->buildEntityByDocument($document, $limitImages);
            }
        }
        $listMessiers = array_filter($listMessiers, function (Messier $messier) use ($excludedMessier) {
            return $messier->getId() !== $excludedMessier;
        });

        return $listMessiers;
    }

    /**
     * @param $start
     * @param $to
     * @return array
     */
    public function getList($from, $size, $order, $nbImages = 1)
    {
        $listMessiers = [];
        /** @var  $listItems */
        $listItems = $this->findBy('match_all', [], [], $order, $from, $size);
        if (!is_null($listItems) && 0 < $listItems->getTotal()) {
            foreach ($listItems->getDocuments() as $document) {
                $listMessiers[] = $this->buildEntityByDocument($document, $nbImages);
            }
        }

        return [$listItems->getTotal(), $listMessiers];
    }


    /**
     * TODO : make a factory pattern
     *
     * @param Document $kuzzleDocument
     * @param $limitImages
     * @return Messier
     */
    private function buildEntityByDocument(Document $kuzzleDocument, $limitImages = 1)
    {
        $kuzzleEntity = $this->getKuzzleEntity();
        /** @var Messier $messier */
        $messier = new $kuzzleEntity;

        $id = $kuzzleDocument->getId();
        $messier->buildObject($kuzzleDocument)->setId($id);
        $this->urlHelper->generateUrl($messier);

        try {
            $astrobinImage = $this->wsGetImage->getImagesBySubject($id, $limitImages);
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
