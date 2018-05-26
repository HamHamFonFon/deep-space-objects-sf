<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 21/04/18
 * Time: 20:12
 */

namespace AppBundle\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleSubscriber
 * @package AppBundle\EventSubscriber
 */
class LocaleSubscriber implements EventSubscriberInterface
{

    private $defaultLocale;

    protected $locale;

    /**
     * LocaleSubscriber constructor.
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }


    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        if ($locale = $request->attributes->get('_locale')) {
            $this->locale = $request->getSession()->set('_locale', $locale);
        } else {
            $this->locale = $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    /**
     * Returne current locale
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                [
                    'onKernelRequest', 20
                ]
            ]
        ];
    }

}