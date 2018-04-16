<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 23:26
 */

namespace AppBundle\Entity;


use Kuzzle\Document;

/**
 * Class abstractKuzzleDocumentEntity
 * @package AppBundle\Entity
 */
abstract class abstractKuzzleDocumentEntity
{
    /** @var  */
    private $id;

    /**
     * abstractKuzzleDocumentEntity constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $object = new static();
        if (isset($document['_id'])) {
            $object->setId($document->getId());
        }

        if (isset($document['body']) && !empty($document['body'])) {
            foreach ($document['body'] as $key => $value) {
                // TODO : setKey($value)
            }
        }

        return $object;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    public function buildUrl()
    {

    }
}