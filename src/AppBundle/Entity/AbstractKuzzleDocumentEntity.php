<?php

namespace AppBundle\Entity;

use Kuzzle\Document;

/**
 * Class abstractKuzzleDocumentEntity
 * @package AppBundle\Entity
 */
abstract class AbstractKuzzleDocumentEntity
{
    /** @var  */
    private $id;

    /** @var  */
    private $kuzzleId;

    /**
     * @param Document $document
     * @return $this
     */
    public function buildObject(Document $document)
    {
        if ($document->getId()) {
            $this->setKuzzleId($document->getId());
        }

        if ($document->getContent()) {
            foreach ($document->getContent() as $field => $data) {
                $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
                if ("geometry" == $field) {
                    // TODO later
                    continue;
                }
                if (is_array($data) && "geometry" != $field) {
                    $object = $this;
                    array_walk($data, function($value, $field) use (&$object) {
                        $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
                        $object->$method($value);
                    });
                } else {
                    if (true === method_exists($this, $method)) {
                        $this->$method($data);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKuzzleId()
    {
        return $this->kuzzleId;
    }

    /**
     * @param $kuzzleId
     * @return $this
     */
    public function setKuzzleId($kuzzleId)
    {
        $this->kuzzleId = $kuzzleId;
        return $this;
    }
}
