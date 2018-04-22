<?php

namespace AppBundle\Astrobin\Services;

use AppBundle\Astrobin\AstrobinInterface;
use AppBundle\Astrobin\AstrobinWebService;
use AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions;
use AppBundle\Astrobin\Response\AstrobinCollection;
use AppBundle\Astrobin\Response\AstrobinImage;

/**
 * Class getObject
 * @package AppBundle\Astrobin\Services
 */
class getObject extends AstrobinWebService implements AstrobinInterface
{

    /**
     * Return an AstrobinImage()
     *
     * @param $id
     * @return AstrobinImage
     * @throws AstrobinResponseExceptions
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \ReflectionException
     */
    public function getOneImage($id)
    {
        $params = ['subjects' => $id, 'limit' => 1];
        $rawResp = $this->callWs($params);

        /** @var AstrobinImage $astrobinImage */
        $astrobinImage = new AstrobinImage();
        $astrobinImage->fromObj($rawResp[0]);

        return $astrobinImage;
    }



    /**
     * Return a collection of AstrobinImage()
     *
     * @param $id
     * @param $limit
     * @return AstrobinCollection
     * @throws AstrobinResponseExceptions
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \ReflectionException
     */
    public function getManyImages($id, $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            exit;
        }

        $params = ['subjects' => $id, 'limit' => $limit];
        $rawResp = $this->callWs($params);

        $astrobinCollection = new AstrobinCollection();
        $astrobinCollection->setImages($rawResp);

        return $astrobinCollection;
    }


    /**
     * Call WS "image" with parameters
     *
     * @param array $params
     * @return AstrobinImage
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call('image/?', AstrobinWebService::METHOD_GET, $params);

        if (!isset($rawResp->objects) || 0 == count($rawResp->objects)) {
            throw new AstrobinResponseExceptions("Response from Astrobin is empty");
        }
        return $rawResp->objects;
    }
}