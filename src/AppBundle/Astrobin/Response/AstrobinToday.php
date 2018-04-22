<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 22/04/18
 * Time: 17:26
 */

namespace AppBundle\Astrobin\Response;


class AstrobinToday extends AbstractAstrobinResponse
{
    public $date;
    public $image;
    public $resource_uri;
}