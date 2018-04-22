<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:27
 */

namespace AppBundle\Astrobin\Response;

/**
 * Class AstrobinImage
 * @package AppBundle\Astrobin\Response
 */
class AstrobinImage extends AbstractAstrobinResponse
{
    public $title;
    public $description;
    public $url_gallery;
    public $url_thumb;
    public $url_regular;
    public $user;
}