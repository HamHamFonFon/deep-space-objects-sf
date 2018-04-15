<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 15/04/18
 * Time: 20:34
 */

namespace AppBundle\Kuzzle;

use Kuzzle\Kuzzle;

class KuzzleService
{

    protected $kuzzle;
    protected $host;
    protected $index;

    public function __construct($host, $index)
    {
        $this->kuzzle = new Kuzzle($host, $index);
    }

}