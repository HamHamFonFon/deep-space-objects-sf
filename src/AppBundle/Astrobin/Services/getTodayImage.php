<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:16
 */

namespace AppBundle\Astrobin\Services;

use AppBundle\Astrobin\AstrobinInterface;
use AppBundle\Astrobin\AstrobinWebService;
use AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions;
use AppBundle\Astrobin\Response\AstrobinImage;
use AppBundle\Astrobin\Response\AstrobinToday;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class getTodayImage extends AstrobinWebService implements AstrobinInterface
{

    const END_POINT = 'imageoftheday/';

    const FORMAT_DATE_ASTROBIN = "Y-m-d";


    /**
     * @throws AstrobinResponseExceptions
     * @throws \AppBundle\Astrobin\Exceptions\astrobinException
     * @throws \ReflectionException
     */
    public function getTodayImage()
    {
        $rawResp = $this->callWs(['limit' => 1]);

        $astrobinToday = new AstrobinToday();
        $astrobinToday->fromObj($rawResp[0]);

        $today = new \DateTime('now');
        if ($today->format(self::FORMAT_DATE_ASTROBIN) == $astrobinToday->date) {
            dump($astrobinToday->image);
            if (preg_match('/\/([\d]+)/', $astrobinToday->image, $matches)) {
                $imageId = $matches[1];
                $sndRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);
                dump($sndRawCall);
            }
        }

        die();
    }


    /**
     * @param array $params
     * @return AstrobinImage
     * @throws \AppBundle\Astrobin\Exceptions\astrobinException
     */
    public function callWs($params = [])
    {
        /** @var  $rawResp */
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new AstrobinResponseExceptions("Response from Astrobin is empty");
        }
        return $rawResp->objects;
    }


    /**
     * @param array $objects
     * @return array
     */
    public function responseWs($objects = [])
    {
        $astrobinResponse = [];
        return $astrobinResponse;
    }
}