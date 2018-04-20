<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:16
 */

namespace AppBundle\Astrobin\Services;


use AppBundle\Astrobin\astrobinInterface;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class getTodayImage extends astrobinWebService implements astrobinInterface
{


    public function callWs()
    {
        /** @var  $rawResp */
        $rawResp = $this->call('imageoftheday/?limit=1', astrobinWebService::METHOD_GET, null);
    }
}