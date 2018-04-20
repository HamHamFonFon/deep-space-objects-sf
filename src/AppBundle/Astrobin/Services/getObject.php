<?php

namespace AppBundle\Astrobin\Services;

use AppBundle\Astrobin\astrobinInterface;
use AppBundle\Astrobin\AstrobinWebService;
use AppBundle\Astrobin\Response\AstroBinImage;

/**
 * Class getObject
 * @package AppBundle\Astrobin\Services
 */
class getObject extends AstrobinWebService implements astrobinInterface
{

    /**
     * @param $id
     * @return AstroBinImage
     */
    public function getOneImage($id)
    {
        $params = ['subjects' => $id, 'limit' => 1];
        return $this->callWs($params);
    }

    /**
     * @param $id
     * @param $limit
     * @return AstroBinImage
     */
    public function getManyImages($id, $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            exit;
        }

        $params = ['subjects' => $id, 'limit' => $limit];
        return $this->callWs($params);
    }


    /**
     * @param array $params
     * @return AstroBinImage
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call('image/?', astrobinWebService::METHOD_GET, $params);

        $response = new AstroBinImage();
        $response->fromObj($rawResp);

        return $response;
    }
}