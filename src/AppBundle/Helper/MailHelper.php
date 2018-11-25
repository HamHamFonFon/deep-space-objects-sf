<?php

namespace AppBundle\Helper;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class MailHelper
 * @package AppBundle\Helper
 */
class MailHelper
{

    const MIME_HTML = 'text/html';
    const MIME_TEXT = 'text/plain';

    private $mailer;

    private $templateEngine;

    protected $locale;

    private $logger;
    /**
     * MailHelper constructor.
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templateEngine
     * @param $locale
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templateEngine, $locale, Logger $logger)
    {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
        $this->locale = $locale;
        $this->logger = $logger;
    }


    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $template
     * @param $content
     * @return int
     */
    public function sendMail($from, $to, $subject, $template, $content): int
    {
        try {
            /** @var \Swift_Message $message */
            $message = \Swift_Message::newInstance();

            $message->setTo($to)
                ->setFrom($from)
                ->setSubject($subject);

            if (array_key_exists('html', $template)) {
                $htmlContent = $this->templateEngine->render($template, $content);
                $message->setBody($htmlContent, self::MIME_HTML);
            }

            if (array_key_exists('text', $template)) {
                $textContent = $this->templateEngine->render($template, $content);
                $message->addPart($textContent, self::MIME_TEXT);
            }
            return $this->mailer->send($message);

        } catch (\Swift_SwiftException $e) {
            $this->logger->addError($e->getMessage());
        }
    }

}