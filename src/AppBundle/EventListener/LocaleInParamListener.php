<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class LocaleInParamListener implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var array
     */
    private $supportedLocales;

    /**
     * @var string
     */
    private $localeRouteParam;


    /**
     * LocaleInParamListener constructor.
     * @param RouterInterface $router
     * @param string $defaultLocale
     * @param array $supportedLocales
     * @param string $localeRouteParam
     */
    public function __construct(RouterInterface $router, $defaultLocale = 'en', array $supportedLocales = ['en', 'fr', 'de', 'es', 'pt'], $localeRouteParam = '_locale')
    {
        $this->router = $router;
        $this->defaultLocale = $defaultLocale;
        $this->supportedLocales = $supportedLocales;
        $this->localeRouteParam = $localeRouteParam;
    }

    /**
     * @param $locale
     * @return bool
     */
    public function isLocaleSupported($locale)
    {
        return in_array($locale, $this->supportedLocales);
    }


    /**
     * @param GetResponseEvent $event
     * @throws \Exception
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->get($this->localeRouteParam);

        if(null !== $locale) {
            $routeName = $request->get('_route');

            if(!$this->isLocaleSupported($locale)) {
                $routeParams = $request->get('_route_params');

                if (!$this->isLocaleSupported($this->defaultLocale)) {
                    throw new \Exception("Default locale is not supported.");
                }

                $routeParams[$this->localeRouteParam] = $this->defaultLocale;
                $url = $this->router->generate($routeName, $routeParams);
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => [
                [
                    'onKernelRequest', 20
                ]
            ]
        );
    }
}