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

    /** @var  */
    private $kuzzleId;


    /**
     * abstractKuzzleDocumentEntity constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $object = new static();
        if (isset($document['_id'])) {
            $object->setKuzzleId($document->getId());
        }

        if (isset($document['body']) && !empty($document['body'])) {

//            TODO
//            $object->setId('TODO');
            foreach ($document['body'] as $key => $value) {
                $method = 'set'.ucfirst($key);

                if (true === method_exists($object, $method)) {
                    $object->$method($value);
                } else {
                    $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                    if (true === method_exists($object, $method)) {
                        $object->$method($value);
                    }
                }
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