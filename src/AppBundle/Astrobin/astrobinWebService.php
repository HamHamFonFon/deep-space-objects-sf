<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 20:07
 */

namespace AppBundle\Astrobin\Services;

/**
 * Class astrobinWebService
 * @package AppBundle\Astrobin\Services
 */
abstract class astrobinWebService
{

    const ASTROBIN_URL = 'http://astrobin.com/api/v1/';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    private $apiKey;
    private $apiSecret;

    /**
     * astrobinCallApi constructor.
     */
    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }



    /**
     * @param $endPoint
     * @param $method
     * @param $data
     * @return mixed
     */
    public function call($endPoint, $method, $data)
    {
        $curl = $this->initCurl($endPoint, $method, $data);

        $resp = curl_exec($curl);
        if( ! $result = curl_exec($curl)) {
            // show problem, genere exception
            curl_error($curl);
        }

        $infoCurl = curl_getinfo($curl);

        curl_close($curl);

        if (is_string($resp)) {
            // TODO 1 verification if start with { : not : log exception

            $obj = json_decode($resp);
            // Check ig json_decode fail
            if (!$obj) {

            }

//            TODO : check return of WS
        }

        return $obj;
    }


    /**
     *
     */
    public function initCurl($endPoint, $method, $data)
    {
        $curl = curl_init();

        $url = self::ASTROBIN_URL . $endPoint;
        if (is_array($data)) {

        } else {
            $url .= $data;
        }

        $options = [
            'CURLOPT_URL' => $url . '&api_key=' . $this->apiKey . '&api_scret=' . $this->apiSecret . '&format=json',
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HEADER' => 0,
            'CURLOPT_TIMEOUT' => 4

        ];


        curl_setopt_array($curl, $options);

        return $curl;
    }

}