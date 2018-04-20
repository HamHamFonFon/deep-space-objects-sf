<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 20/04/18
 * Time: 21:32
 */

namespace AppBundle\Astrobin\Services;


use AppBundle\Astrobin\astrobinInterface;
use AppBundle\Astrobin\AstrobinWebService;


/**
 * Class getImagesUser
 * @package AppBundle\Astrobin\Services
 */
class getImagesUser extends AstrobinWebService implements astrobinInterface
{

    /**
     * @param $user
     * @param $limit
     * @return getImagesUser
     */
    public function getImagesFromUser($user, $limit)
    {
        return $this->callWs(['user' => $user, 'limit' => $limit]);
    }



    /**
     * @param null $params
     * @return static
     */
    public function callWs($params = null)
    {
        $rawResp = $this->call('image/?', parent::METHOD_GET, $params);

        // TODO make response
        $imgUser = new static();
        return $imgUser;
    }

}