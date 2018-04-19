<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 20:09
 */

namespace AppBundle\Astrobin\Services;


use AppBundle\Astrobin\Response\AstroBinImage;

/**
 * Class getObject
 * @package AppBundle\Astrobin\Services
 */
class getObject extends astrobinWebService
{

    /**
     * @param $id
     * @return AstroBinImage
     */
    public function callWs($id)
    {
        $rawResp = $this->call('image/?subjects', astrobinWebService::METHOD_GET, $id);

        $response = new AstroBinImage();
        $response->fromObj($rawResp);

        return $response;
    }
}