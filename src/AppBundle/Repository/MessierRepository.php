<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Messier;
use AppBundle\Helper\GenerateUrlHelper;
use AppBundle\Kuzzle\KuzzleHelper;
use Astrobin\Services\GetImage;
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
            $kuzzleDocument = $result->getDocuments()[0];
            $messier = new Messier();
            $messier->buildObject($kuzzleDocument)->setId($id);
            $this->urlHelper->generateUrl($messier);

            try {
                $astrobinImage = $this->wsGetImage->getImagesBySubject($id, 6);
                $messier->addImages($astrobinImage);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }

        return $messier;
    }

    /**
     * Get objects messiers by type, filtered by constellation
     *
     * @param $type
     * @param null $const
     * @param $sort
     * @return array
     */
    public function getMessiersByType($type, $const = null, $sort)
    {
        $listMessiers = [];
        $results = $this->findBy('term', ['properties.type' => $type], ['properties.const_id' => ucfirst($const)], [], 0, 20);
        if (0 < $results->getTotal()) {
            foreach ($results->getDocuments() as $document) {
                $messier = new Messier();
                $messier->buildObject($document);

                dump($messier);
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
     * @return string
     */
    protected function getKuzzleEntity()
    {
        return '\AppBundle\Entity\Messier';
    }
}
