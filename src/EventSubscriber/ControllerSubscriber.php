<?php

namespace App\EventSubscriber;

use App\Entity\Permissoes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ControllerSubscriber implements EventSubscriberInterface
{
    private $em;
    private $tokenStorage;
    private $session;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, SessionInterface $session)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->session = $session;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['onKernelController', 10],
            ],
        ];
    }

    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMasterRequest()) return;
        if (!$token = $this->tokenStorage->getToken()) return;
        if (!$token->isAuthenticated()) return;
        if (!$user = $token->getUser()) return;
        if ($user == "anon.") return;

        $request = $event->getRequest();
        $route  = $request->attributes->get('_route');

        // salva as permissões na session, para minimizar os requests em bd
        //if (empty($this->session->get("permissoes"))){
            if($token->getUser()){
                $grupo = $token->getUser()->getGrupo()->getId();
                $permissoes = $this->em->getRepository(Permissoes::class)->findBy(array("grupo" => $grupo));

                $session = new Session(new NativeSessionStorage(), new AttributeBag());
                $session->set('permissoes', $permissoes);
            }
        //}

        if ($user->getGrupo()->getId() == 1) return;

        $permissoes = $this->session->get("permissoes");

        $bloqueado = true;
        /**
         * @var $p Permissoes
         */
        foreach ($permissoes as $p){
            if($p->getRota() == $route || substr($route, 0, 1) == "_"){
                $bloqueado = false;
                break;
            }
        }

        if($bloqueado){
            throw new \Exception("<b>Sem permissão nesta rota</b> '".$route."'");
        }
    }
}