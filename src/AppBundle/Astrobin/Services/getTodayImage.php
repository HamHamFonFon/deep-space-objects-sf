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
use AppBundle\Astrobin\Response\AstrobinImage;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class getTodayImage extends AstrobinWebService implements AstrobinInterface
{

    /**
     * @return AstrobinImage
     * @throws \AppBundle\Astrobin\Exceptions\astrobinException
     */
    public function getTodayImage()
    {
        return $this->callWs(['limit' => 1]);
    }


    /**
     * @param array $params
     * @return AstrobinImage
     * @throws \AppBundle\Astrobin\Exceptions\astrobinException
     */
    public function callWs($params = [])
    {
        /** @var  $rawResp */
        $rawResp = $this->call('imageoftheday/?', parent::METHOD_GET, $params);
        dump($rawResp); die();
//        dump($rawResp);
        $response = new AstrobinImage();
        $response->fromObj($rawResp);

        return $response;
    }
}