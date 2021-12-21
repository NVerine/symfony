<?php

namespace App\EventSubscriber;

use App\Service\Notify;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $notify;

    public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException',
        );
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $files = $exception->getTrace();

        $trace = "";
        $i = 0;
        foreach ($files as $r) {
            if ($i == 0 && $exception->getFile() != $r["file"]) {
                $f = explode("/src/", $exception->getFile());
                if (count($f) <= 1){
                    $f = explode("/vendor/", $exception->getFile());
                    $trace .= "- " . $f[1] . "::" . $exception->getLine();
                }
                else{
                    $trace .= "- " . $f[1] . "::" . $exception->getLine();
                }
            }
            if (str_contains($r["file"], '/src/')) {
                $f = explode("/src/", $r["file"]);
                if (!empty($trace))
                    $trace .= " <br>";
                $trace .= "- " . $f[1] . "::" . $r["line"];
            }
            $i++;
        }

        $trace = $exception->getMessage() . " <br><br> " . $trace;

        if (empty($trace)) {
            $f = explode("/symfony/", $exception->getFile());
            $file = $f[1] . "::" . $exception->getLine() . "<br>";

            $trace = $file . $exception->getMessage();
        }

        $this->notify->addMessage($this->notify::ERROR, $trace);
        $customResponse = JsonResponse::fromJsonString(
            $this->notify->newReturn(""), 200,
            array('Symfony-Debug-Toolbar-Replace' => 1)
        );

        // atualiza para status 200
        $event->allowCustomResponseCode();
        $event->setResponse($customResponse);
    }
}