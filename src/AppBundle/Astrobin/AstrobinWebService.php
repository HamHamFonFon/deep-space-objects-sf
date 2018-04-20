<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 20/04/18
 * Time: 18:34
 */

namespace AppBundle\Astrobin;

/**
 * Class AstrobinWebService
 * @package AppBundle\Astrobin
 */
abstract class AstrobinWebService
{
    const ASTROBIN_URL = 'http://astrobin.com/api/v1/';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    private $apiKey;
    private $apiSecret;


    /**
     * AstrobinWebService constructor.
     * @param $apiKey
     * @param $apiSecret
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

        if(!$resp = curl_exec($curl)) {
            // show problem, genere exception
            curl_error($curl);
        }

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
     * Build cURL URL
     * @param $endPoint
     * @param $method
     * @param $data
     * @return resource
     */
    public function initCurl($endPoint, $method, $data)
    {
        $curl = curl_init();

        $url = self::ASTROBIN_URL . $endPoint;
        if (is_array($data)) {

        } else {
            $url .= $data;
        }

        $params = [
            'api_key' => $this->apiKey,
            'api_scret' => $this->apiSecret,
            'format' => 'json'
        ];

        $url .= implode('', array_map(function($v, $k) {
            return sprintf("&%d=%d", $v, $k);
        }, array_keys($params), $params));

        dump($url);

        $options = [
            'CURLOPT_URL' => $url,
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HEADER' => 0,
            'CURLOPT_TIMEOUT' => 4

        ];

        curl_setopt_array($curl, $options);

        return $curl;
    }
}