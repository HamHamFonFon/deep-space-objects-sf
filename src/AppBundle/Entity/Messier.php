<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 23:25
 */

namespace AppBundle\Entity;
use AppBundle\Repository\MessierRepository;
use Kuzzle\Document;

/**
 * Class Messier
 * @package AppBundle\Entity
 */
class Messier extends abstractKuzzleDocumentEntity
{


    /**
     * Messier constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document);
    }


    /**
     * @return string
     */
    public static function getCollectionName()
    {
        return MessierRepository::COLLECTION_NAME;
    }

}