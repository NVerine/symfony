<?php

namespace App\EventSubscriber;

use App\Entity\Permissions;
use App\Exception\BusinessException;
use App\Repository\PermissionsRepository;
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
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;
    private SessionInterface $session;

    /**
     * ControllerSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, SessionInterface $session)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @return \array[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['onKernelController', 10],
            ],
        ];
    }

    /**
     * @throws BusinessException
     */
    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMasterRequest()) return;
        if (!$token = $this->tokenStorage->getToken()) return;
        if (!$token->isAuthenticated()) return;
        if (!$user = $token->getUser()) return;
        if ($user == "anon.") return;

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // save permission on session to lower DB usage
        //if (empty($this->session->get("permissoes"))){
        if ($token->getUser()) {
            $grupo = $token->getUser()->getGroup()->getId();
            $permissoes = $this->em->getRepository(Permissions::class)->findBy(array("group" => $grupo));

            $session = new Session(new NativeSessionStorage(), new AttributeBag());
            $session->set('permissions', $permissoes);
        }
        //}

        $bloqueado = true;

        if (!in_array($route, PermissionsRepository::$blocked_routes)) {
            // superadmin has bypass on dev
            if ($user->getGroup()->getId() == 1) return;

            $permissoes = $this->session->get("permissions");
            /**
             * @var $p Permissions
             */
            foreach ($permissoes as $p) {
                if ($p->getRoute() == $route || substr($route, 0, 1) == "_") {
                    $bloqueado = false;
                    break;
                }
            }
        }

        if ($bloqueado) {
            throw new BusinessException("No permission in this route '{$route}'");
        }
    }
}