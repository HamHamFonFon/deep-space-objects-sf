<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 22/04/18
 * Time: 14:10
 */

namespace AppBundle\Astrobin\Response;

/**
 * Class AstrobinCollection
 * @package AppBundle\Astrobin\Response
 */
class AstrobinCollection extends AbstractAstrobinResponse
{
    public $images;


    /**
     * TODO : find equivalent of Doctrine ArrayCollection
     * @param $images
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function setImages($images)
    {
        $listImages = [];
        foreach ($images as $image) {
            $listImages[] = $this->fromObj($image);
        }
        $this->images = $listImages;
    }


}