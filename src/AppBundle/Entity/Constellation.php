<?php

namespace AppBundle\Entity;

/**
 * Class Constellation
 * @package AppBundle\Entity
 */
class Constellation extends AbstractKuzzleDocumentEntity
{
    protected $locale;
    protected $id;
    protected $gen;
    protected $rank;
    protected $alt;
    protected $hem;
    protected $geometry;

    /**
     * @param string $locale
     * @return Dso
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public static function getCollectionName()
    {
        return 'constelations'; //TODO change into Repository
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
    public function getGen()
    {
        return $this->gen;
    }

    /**
     * @param mixed $gen
     */
    public function setGen($gen)
    {
        $this->gen = $gen;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
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
    public function getHem()
    {
        return $this->hem;
    }

    /**
     * @param mixed $hem
     */
    public function setHem($hem)
    {
        $this->hem = $hem;
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
     */
    public function setGeometry($geometry)
    {
        $this->geometry = $geometry;
    }
}