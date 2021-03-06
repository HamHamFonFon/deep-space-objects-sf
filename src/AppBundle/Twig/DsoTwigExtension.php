<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 08/05/18
 * Time: 21:31
 */

namespace AppBundle\Twig;
use Symfony\Component\Intl\Locale\Locale;

/**
 * Class TwigExtension
 * @package AppBundle\Twig
 */
class DsoTwigExtension extends \Twig_Extension
{
    const PC = 0.3066020852;

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('convert_al_pc', [$this, 'convertAlToPc']),
            new \Twig_SimpleFilter('is_instance_of', [$this, 'isInstanceOf']),
            new \Twig_SimpleFilter('number_format_by_locale', [$this, 'numberFormatByLocale'])
        ];
    }


    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('uasort', [$this, 'uasort']),
            new \Twig_SimpleFunction('remove_element', [$this, 'removeElement'])
        ];
    }


    /**
     * Convert distance in Light-Year into Parsec
     * @param $dist
     * @return float|int
     */
    public function convertAlToPc($dist)
    {
        return $this->numberFormatByLocale($dist*(self::PC));
    }


    /**
     * @param $object
     * @param $class
     * @return bool
     */
    public function isInstanceOf($object, $class)
    {
        return is_a($object, $class, true) ? true: false;
    }


    /**
     * @param $number
     * @return string
     */
    public function numberFormatByLocale($number)
    {
        $localeInfo = localeconv();
        if (!is_null($number)) {
            $number = number_format($number, 2, $localeInfo['decimal_point'], $localeInfo['thousands_sep']);
        }
        return $number;
    }

    /**
     * @param $tab
     * @param $key
     * @return
     */
    public function uasort($tab, $key)
    {
        uasort($tab, function($a, $b) use($key) {
            return ($a[$key] < $b[$key]) ? -1 : 1;
        });

        return $tab;
    }

    /**
     * @param $arr
     * @param $value
     * @return mixed
     */
    public function removeElement($arr, $value)
    {
        $index = array_search($value, $arr);
        unset($arr[$index]);

        return $arr;
    }
}
