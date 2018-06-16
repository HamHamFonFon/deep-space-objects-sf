<?php

namespace AppBundle\Helper;

use AppBundle\Entity\AbstractKuzzleDocumentEntity;
use AppBundle\Entity\Constellation;
use AppBundle\Entity\Dso;
use AppBundle\Entity\Messier;
use AppBundle\Repository\ConstellationRepository;
use AppBundle\Repository\DsoRepository;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class GenerateUrlHelper
 * @package AppBundle\Helper
 */
class GenerateUrlHelper
{
    /** @var RouterInterface */
    private $router;

    /**
     * GenerateUrlHelper constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Dso|AbstractKuzzleDocumentEntity $entity
     * @param $absoluteUrl
     * @return AbstractKuzzleDocumentEntity|Dso|Constellation
     */
    public function generateUrl($entity, $absoluteUrl = false)
    {
        $url = null;
        if ($entity) {
            switch ($entity::getCollectionName()) {
                case DsoRepository::COLLECTION_NAME:
                    $url = $this->router->generate('dso_full', ['catalog' => strtolower($entity->getCatalog()), 'objectId' => strtolower($entity->getId())], $absoluteUrl);
                    break;
                case 'planet':
                    break;
                case ConstellationRepository::COLLECTION_NAME:
                    $url = $this->router->generate('constellation_full', ['id' => $entity->getId()], $absoluteUrl);
                    break;
                default:
                    $url = $this->router->generate('homepage');
            }
            $entity->full_url = $url;
        }
        return $entity;
    }
}
