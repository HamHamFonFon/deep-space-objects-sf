<?php

namespace AppBundle\Helper;

use AppBundle\Entity\AbstractKuzzleDocumentEntity;
use AppBundle\Entity\Messier;
use AppBundle\Repository\MessierRepository;
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
     * @param Messier|AbstractKuzzleDocumentEntity $entity
     * @param $absoluteUrl
     * @return AbstractKuzzleDocumentEntity|Messier
     */
    public function generateUrl($entity, $absoluteUrl = false)
    {
        $url = null;
        if ($entity) {
            switch ($entity::getCollectionName()) {
                case MessierRepository::COLLECTION_NAME:
                    $url = $this->router->generate('messier_full', ['objectId' => $entity->getId()], $absoluteUrl);
                    break;
                case 'planet':
                case 'ngc':
                case 'constellation':
                default:
                    $url = $this->router->generate('homepage');
            }

            $entity->full_url = $url;
        }
        return $entity;
    }
}
