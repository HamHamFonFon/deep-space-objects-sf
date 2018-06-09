<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Dso;
use AppBundle\Entity\Messier;
use AppBundle\Helper\GenerateUrlHelper;
use AppBundle\Kuzzle\KuzzleHelper;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Image;
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

    const SEARCH_SIZE = 12;

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
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
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
            $dso = $this->buildEntityByDocument($result->getDocuments()[0], 7);
        }

        return $dso;
    }


    /**
     * Retrieve objects from same constellation
     *
     * @param $constId
     * @param $excludedObject
     * @param int $limit
     * @param int $limitImages
     * @return array
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getObjectsByConst($constId, $excludedObject, $limit = 10, $limitImages = 1)
    {
        $list = [];
        $results = $this->findBy('term', ['data.const_id' => $constId], [], ['data.mag' => 'ASC'],0, $limit);
        if (0 < $results->getTotal()) {
            foreach ($results->getDocuments() as $document) {
                $list[] = $this->buildEntityByDocument($document, $limitImages);
            }
        }
        $list = array_filter($list, function (Dso $dso) use ($excludedObject) {
            return $dso->getId() !== $excludedObject;
        });

        return $list;
    }

    /**
     * @deprecated
     * Get objects messiers by type, filtered by constellation
     *
     * @param $type
     * @param $excludedMessier
     * @param int $limit
     * @param int $limitImages
     * @return array
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
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
     * @param $typeCatalog
     * @param $from
     * @param $size
     * @param $order
     * @param int $nbImages
     * @return array
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getList($typeCatalog, $filters, $from, $size, $order, $nbImages = 1)
    {
        $listDso = [];
        $filters = [
            'AND' => $filters
        ];

        $aggregates = [
            'aggregates' => [
                'type' => [
                    'terms' => [
                        'field' => 'data.type.keyword',
                        'size' => 20
                    ]
                ],
                'const_id' => [
                    'terms' => [
                        'field' => 'data.const_id.keyword',
                        'size' => 100
                    ]
                ],
                'mag' => [
                    'range' => [
                        'field' => 'data.mag',
                        'ranges' => [
                            ['to' => 5],
                            ['from' => 5, 'to' => 10],
                            ['from' => 10]
                        ]
                    ]
                ]
            ],
            'filter' => [
                'term' => [
                    'catalog' => $typeCatalog
                ]
            ]
        ];

        /** @var  $listItems */
        $listItems = $this->findBy('term', ['catalog' => $typeCatalog], $filters, $order, $from, $size, $aggregates);
        if (!is_null($listItems) && 0 < $listItems->getTotal()) {
            foreach ($listItems->getDocuments() as $document) {
                $listDso[] = $this->buildEntityByDocument($document, $nbImages);
            }
            $listAggregates = $listItems->getAggregations();
        }

        return [$listItems->getTotal(), $listDso, $listAggregates];
    }


    /**
     * @param Document $kuzzleDocument
     * @param int $limitImages
     * @return Dso
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    private function buildEntityByDocument(Document $kuzzleDocument, $limitImages = 1)
    {
        // TODO : make a factory pattern
        $kuzzleEntity = $this->getKuzzleEntity();

        /** @var Dso $dso */
        $dso = new $kuzzleEntity;

        $dso->setLocale($this->getLocale())->buildObject($kuzzleDocument);
        $this->urlHelper->generateUrl($dso);

        if (!is_null($dso->getAstrobinId())) {
            $astrobinImage = $this->wsGetImage->getImageById($dso->getAstrobinId());
            $dso->addImageCover($astrobinImage);
        }

        if (0 < $limitImages) {
            try {
                $astrobinListImage = $this->wsGetImage->getImagesBySubject($dso->getId(), $limitImages);

                // If there is no astrobinId we try to add the first image as image cover
                if (is_null($dso->getAstrobinId())) {
                    if ($astrobinListImage instanceof Image) {
                        $dso->addImageCover($astrobinListImage);
                    } else {
                        $dso->addImageCover($astrobinListImage->listImages[0]);
                    }
                }
                $dso->addImages($astrobinListImage);
            } catch (\Exception $e) {
//            dump($e->getMessage());
            }
        }

        return $dso;
    }


    /**
     * @param $kuzzleId
     * @param $typeVote
     * @return Dso
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function updateVote($kuzzleId, $typeVote)
    {
        $valueMapping = [
            'up' => 1,
            'down' => -1
        ];

        $document = $this->getKuzzleDocument($kuzzleId);
        $contentDoc = $document->getContent();
        $val = $valueMapping[$typeVote];
        $nbVote = (isset($contentDoc['vote']['nb_vote']))? $contentDoc['vote']['nb_vote'] : 0;
        $valueVote = (isset($contentDoc['vote']['value_vote'])) ? $contentDoc['vote']['value_vote'] : 0;

        $fields = [
            'vote' => [
                'nb_vote' => $nbVote+1,
                'value_vote' => $valueVote + $val
            ]
        ];

        $document = $this->updateDocument($document, $fields);
        return $this->buildEntityByDocument($document, 0);
    }


    /**
     * @return string
     */
    public function getKuzzleEntity()
    {
        return '\AppBundle\Entity\Dso';
    }
}
