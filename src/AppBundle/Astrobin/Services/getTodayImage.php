<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:16
 */

namespace AppBundle\Astrobin\Services;


class getTodayImage extends astrobinWebService
{


    public function callWs()
    {
        /** @var  $rawResp */
        $rawResp = $this->call('imageoftheday/?limit=1', astrobinWebService::METHOD_GET, null);
    }
}