<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 20:34
 */

namespace AppBundle\Kuzzle;

use Kuzzle\Kuzzle;

/**
 * Class KuzzleService
 * @package AppBundle\Kuzzle
 */
class KuzzleService
{

    /** @var Kuzzle  */
    protected $kuzzle;
    protected $host;
    protected $index;


    /**
     * KuzzleService constructor.
     * @param $host
     * @param $index
     */
    public function __construct($host, $index, $port)
    {
        $this->host = $host;
        $this->index = $index;
        $this->port = $port;

        $this->kuzzle = new Kuzzle($this->host, [
            'defaultIndex' => $this->index,
            'port' => $this->port
            ]
        );
    }

}