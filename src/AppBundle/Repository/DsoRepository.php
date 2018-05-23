<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Dso;
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
class DsoRepository extends AbstractKuzzleRepository
{

    const COLLECTION_NAME = 'dso';

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
     * Get Object in Kuzzle and return it
     * @param $id
     * @return Dso|null $dso
     */
    public function getObject($id)
    {
        $dso = null;
        if (is_null($id)) {
            return null;
        }

        /** @var SearchResult $result */
        $result = $this->findById($id);
        if (0 < $result->getTotal()) {
            $dso = $this->buildEntityByDocument($result->getDocuments()[0], 6);
        }

        return $dso;
    }


    /**
     * @deprecated
     * @param $constId
     * @param $excludedMessier
     * @param $limit
     * @param $limitImages
     * @return array
     */
    public function getMessiersByConst($constId, $excludedMessier, $limit = 10, $limitImages = 1)
    {
        $listMessiers = [];
        $results = $this->findBy('term', ['data.const_id' => $constId], [], ['data.magnitude' => 'ASC'],0, $limit);
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
     * @deprecated
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
        $results = $this->findBy('term', ['data.type' => $type], [], [], 0 ,$limit);
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
    public function getList($typeCatalog, $from, $size, $order, $nbImages = 1)
    {
        $listDso = [];
        /** @var  $listItems */
        $listItems = $this->findBy('term', ['catalog' => $typeCatalog], [], $order, $from, $size);
        if (!is_null($listItems) && 0 < $listItems->getTotal()) {
            foreach ($listItems->getDocuments() as $document) {
                $listDso[] = $this->buildEntityByDocument($document, $nbImages);
            }
        }

        return [$listItems->getTotal(), $listDso];
    }


    /**
     *
     * @param Document $kuzzleDocument
     * @param $limitImages
     * @return Messier
     */
    private function buildEntityByDocument(Document $kuzzleDocument, $limitImages = 1)
    {
        // TODO : make a factory pattern
        $kuzzleEntity = $this->getKuzzleEntity();

        /** @var Dso $dso */
        $dso = new $kuzzleEntity;

        $dso->buildObject($kuzzleDocument);
        $this->urlHelper->generateUrl($dso);
        try {
            if (!is_null($dso->getAstrobinId())) {
                $astrobinImage = $this->wsGetImage->getImageById($dso->getAstrobinId());
            } else {
                $astrobinImage = $this->wsGetImage->getImagesBySubject($dso->getId(), $limitImages);
            }
            $dso->addImages($astrobinImage);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
        return $dso;
    }


    /**
     * @return string
     */
    public function getKuzzleEntity()
    {
        return '\AppBundle\Entity\Dso';
    }
}
