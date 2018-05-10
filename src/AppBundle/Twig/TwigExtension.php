<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 08/05/18
 * Time: 21:31
 */

namespace AppBundle\Twig;

/**
 * Class TwigExtension
 * @package AppBundle\Twig
 */
class TwigExtension extends \Twig_Extension
{
    const PC = 0.3066020852;

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('convert_al_pc', [$this, 'convertAlToPc'])
        ];
    }


    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [

        ];
    }


    /**
     * Convert distance in Light-Year into Parsec
     * @param $dist
     * @return float|int
     */
    public function convertAlToPc($dist)
    {
        return $dist*(self::PC);
    }
}
