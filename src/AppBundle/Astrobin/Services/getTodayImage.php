<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:16
 */

namespace AppBundle\Astrobin\Services;


use AppBundle\Astrobin\astrobinInterface;
use AppBundle\Astrobin\AstrobinWebService;
use AppBundle\Astrobin\Response\AstroBinImage;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class getTodayImage extends AstrobinWebService implements astrobinInterface
{

    /**
     * @return AstroBinImage
     * @throws \AppBundle\Astrobin\Exceptions\astroBinException
     */
    public function getTodayImage()
    {
        return $this->callWs(['limit' => 1]);
    }


    /**
     * @param array $params
     * @return AstroBinImage
     * @throws \AppBundle\Astrobin\Exceptions\astroBinException
     */
    public function callWs($params = [])
    {
        /** @var  $rawResp */
        $rawResp = $this->call('imageoftheday/?', parent::METHOD_GET, $params);
//        dump($rawResp);
        $response = new AstroBinImage();
        $response->fromObj($rawResp);

        return $response;
    }
}