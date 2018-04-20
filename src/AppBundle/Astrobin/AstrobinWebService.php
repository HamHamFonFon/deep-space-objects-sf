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
    const LIMIT_MAX = 24;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    protected $timeout;
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
    protected function call($endPoint, $method, $data)
    {
        $obj = null;
        $curl = $this->initCurl($endPoint, $method, $data);

        if(!$resp = curl_exec($curl)) {
            // show problem, genere exception
            curl_error($curl);
        }
        curl_close($curl);

        dump($resp);
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
    private function initCurl($endPoint, $method, $data)
    {
        $curl = curl_init();

        // Build URL with params
        $url = self::ASTROBIN_URL . $endPoint;
        if (is_array($data)) {
            $paramData = implode('&', array_map(function($k, $v) {
                return sprintf("%s=%s", $k, $v);
            }, array_keys($data), $data));
            $url .= $paramData;
        } else {
            $url .= $data;
        }

        // Add keys and format
        $params = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'format' => 'json'
        ];

        $url .= implode('', array_map(function($k, $v) {
            return sprintf("&%s=%s", $k, $v);
        }, array_keys($params), $params));

        // Options CURL
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => $this->timeout,
        ];

        // GET
        if ($method === self::METHOD_GET) {
            array_merge($options, [
//                CURLOPT_CUSTOMREQUEST => self::METHOD_GET,
                CURLOPT_HTTPGET => true,
            ]);
        }

        dump($url); die();
        curl_setopt_array($curl, $options);
        return $curl;
    }
}