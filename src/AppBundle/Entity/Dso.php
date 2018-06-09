<?php

namespace AppBundle\Entity;

use AppBundle\Repository\DsoRepository;

/**
 * Class Dso
 * @package AppBundle\Entity
 */
class Dso extends AbstractKuzzleDocumentEntity
{
    protected $locale;
    protected $id;
    protected $catalog;
    protected $geometry;
    public $images;
    public $imageCover;

    protected $order; // only messier ?
    protected $desig;
    protected $type;
    protected $mag;
    protected $dim;
    protected $alt;
    protected $constId;
    protected $distAl;
    protected $ra;
    protected $dec;
    protected $astrobinId;
    // Vote
    protected $nbVote;
    protected $valueVote;
    protected $stars;
    /**
     * @param string $locale
     * @return Dso
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public static function getCollectionName()
    {
        return DsoRepository::COLLECTION_NAME;
    }

    public function addImageCover($astrobinImage)
    {
        $this->imageCover = $astrobinImage;
    }

    /**
     * @param $astrobinImage
     */
    public function addImages($astrobinImage)
    {
        $this->images = $astrobinImage;
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

    /**
     * @return mixed
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * @param mixed $catalog
     */
    public function setCatalog($catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
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
     */
    public function setDesig($desig)
    {
        $this->desig = $desig;
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
     */
    public function setType($type)
    {
        $this->type = $type;
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
     */
    public function setMag($mag)
    {
        $this->mag = $mag;
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
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     */
    public function setAlt($alt)
    {
        if (empty($this->locale) || 'en' === $this->locale) {
            $this->alt = $alt['alt'];
        } else {
            $this->alt = $alt['alt_' . $this->locale];
        }
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
     */
    public function setConstId($constId)
    {
        $this->constId = $constId;
    }

    /**
     * @return mixed
     */
    public function getDistAl()
    {
        $this->distAl = 1000*$this->distAl;
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

    /**
     * @return mixed
     */
    public function getAstrobinId()
    {
        return $this->astrobinId;
    }

    /**
     * @param mixed $astrobinId
     */
    public function setAstrobinId($astrobinId)
    {
        $this->astrobinId = $astrobinId;
    }

    /**
     * @return mixed
     */
    public function getNbVote()
    {
        return $this->nbVote;
    }

    /**
     * @param mixed $nbVote
     */
    public function setNbVote($nbVote)
    {
        $this->nbVote = $nbVote;
    }

    /**
     * @return mixed
     */
    public function getValueVote()
    {
        return $this->valueVote;
    }

    /**
     * @param mixed $valueVote
     */
    public function setValueVote($valueVote)
    {
        $this->valueVote = $valueVote;
    }


    public function setStars()
    {
//        $this->stars = round(($this->getValueVote()*5)/$this->getNbVote());
    }

    /**
     * @return float|int
     */
    public function getStars()
    {
        $this->stars = 0;
        if (0 < $this->getNbVote()) {
            $this->stars = round(($this->getValueVote()*5)/$this->getNbVote());
        }
        return $this->stars;
    }
}