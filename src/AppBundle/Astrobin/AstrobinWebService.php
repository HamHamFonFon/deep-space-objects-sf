<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 20/04/18
 * Time: 18:34
 */

namespace AppBundle\Astrobin;
use AppBundle\Astrobin\Exceptions\astroBinException;

/**
 * Class AstrobinWebService
 * @package AppBundle\Astrobin
 */
abstract class AstrobinWebService
{
    const ASTROBIN_URL = 'http://www.astrobin.com/api/v1/';
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
     * @return mixed|null
     * @throws astroBinException
     */
    protected function call($endPoint, $method, $data)
    {
        $obj = null;
        $curl = $this->initCurl($endPoint, $method, $data);

        if(!$resp = curl_exec($curl)) {
            // show problem, genere exception
            throw new astroBinException(curl_error($curl));
        }

        // TODO make something with HTTP code...
        $respHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (empty($resp)) {
            throw new astroBinException("Empty Json response from Astrobin");
        }

        if (is_string($resp)) {
            if (false === strpos($resp, '{', 0)) {
                // check if html
                if (false !== strpos($resp, '<html', 0)) {
                    throw new astroBinException(sprintf("Response from Astrobin is in HTML format :\n %s", $resp));
                }
                throw new astroBinException(sprintf("Response from Astrobin is not a JSON valid format :\n %s", $resp));
            }
            $obj = json_decode($resp);

            if (JSON_ERROR_NONE != json_last_error()) {
                throw new astroBinException(json_last_error());
            }

            // Verification of each field if return is a JSON correct
        } else {
            throw new astroBinException("Response from Astrobin is not a string, got ". gettype($resp) . " instead.");
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
        // Build URL with params
        $url = self::ASTROBIN_URL . $endPoint;

        if (is_array($data) && 0 < count($data)) {
            $paramData = implode('&', array_map(function($k, $v) {
                $formatValue = "%s";
                if (is_numeric($v)) {
                    $formatValue = "%d";
                }
                return sprintf("%s=$formatValue", $k, $v);
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

        $curl = curl_init();

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
                CURLOPT_CUSTOMREQUEST => self::METHOD_GET,
                CURLOPT_HTTPGET => true,
            ]);
        }
        dump($url);
        curl_setopt_array($curl, $options);
        return $curl;
    }
}