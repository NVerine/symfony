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
        foreach ($files as $r){
            if (strpos($r["file"], '/src/') !== false) {
                $f = explode("/src/", $r["file"]);
                $trace .= $f[1]."::".$r["line"]."<br>";
            }
        }

        $message = $trace." ".$exception->getMessage();

        $this->notify->addMessage($this->notify::TIPO_ERROR, $message);
        $customResponse = JsonResponse::fromJsonString(
            $this->notify->newReturn(""), 200,
            array('Symfony-Debug-Toolbar-Replace' => 1)
        );

        // atualiza para status 200
        $event->allowCustomResponseCode();
        $event->setResponse($customResponse);
    }
}