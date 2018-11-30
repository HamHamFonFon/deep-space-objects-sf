<?php

namespace AppBundle\Entity;

use AppBundle\Repository\DsoRepository;
use Astrobin\Response\Collection;
use Astrobin\Response\Image;

/**
 * Class Messier
 * @package AppBundle\Entity
 */
class Messier extends AbstractKuzzleDocumentEntity
{

    protected $messierId;
    protected $messierOrder;
    protected $geometry;
    public $images = [];

    protected $name;
    protected $desig;
    protected $alt;
    protected $type;
    protected $mag;
    protected $constId;
    protected $dim;
    protected $cl;
    protected $distAl;
    protected $ra;
    protected $dec;

    const ENTITY_TYPE = 'dso';

    /**
     * @return string
     */
    public static function getCollectionName()
    {
        return DsoRepository::COLLECTION_NAME;
    }

    /**
     * @param Image|Collection $astrobinImage
     */
    public function addImages($astrobinImage)
    {
        $this->images = $astrobinImage;
    }

    /**
     * @return mixed
     */
    public function getMessierId()
    {
        return $this->messierId;
    }

    /**
     * @param mixed $messierId
     * @return Messier
     */
    public function setMessierId($messierId)
    {
        $this->messierId = $messierId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessierOrder()
    {
        return $this->messierOrder;
    }

    /**
     * @param mixed $messierOrder
     * @return Messier
     */
    public function setMessierOrder($messierOrder)
    {
        $this->messierOrder = $messierOrder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeometry()
    {
        return $this->geometry;
    }

    /**
     * @param mixed $geometry
     * @return Messier
     */
    public function setGeometry($geometry)
    {
        $this->geometry = $geometry;
        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     * @return Messier
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Messier
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDesig()
    {
        return $this->desig;
    }

    /**
     * @param mixed $desig
     * @return Messier
     */
    public function setDesig($desig)
    {
        $this->desig = $desig;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     * @return Messier
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Messier
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMag()
    {
        return $this->mag;
    }

    /**
     * @param mixed $mag
     * @return Messier
     */
    public function setMag($mag)
    {
        $this->mag = $mag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConstId()
    {
        return $this->constId;
    }

    /**
     * @param mixed $constId
     * @return Messier
     */
    public function setConstId($constId)
    {
        $this->constId = $constId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDim()
    {
        return $this->dim;
    }

    /**
     * @param mixed $dim
     */
    public function setDim($dim)
    {
        $this->dim = $dim;
    }

    /**
     * @return mixed
     */
    public function getCl()
    {
        return $this->cl;
    }

    /**
     * @param mixed $cl
     */
    public function setCl($cl)
    {
        $this->cl = $cl;
    }

    /**
     * @return mixed
     */
    public function getDistAl()
    {
        return $this->distAl;
    }

    /**
     * @param mixed $distAl
     */
    public function setDistAl($distAl)
    {
        $this->distAl = $distAl;
    }

    /**
     * @return mixed
     */
    public function getRa()
    {
        return $this->ra;
    }

    /**
     * @param mixed $ra
     */
    public function setRa($ra)
    {
        $this->ra = $ra;
    }

    /**
     * @return mixed
     */
    public function getDec()
    {
        return $this->dec;
    }

    /**
     * @param mixed $dec
     */
    public function setDec($dec)
    {
        $this->dec = $dec;
    }
}
