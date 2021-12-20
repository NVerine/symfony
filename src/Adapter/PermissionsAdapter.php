<?php

namespace App\Adapter;

use App\Entity\UsersGroup;
use App\Entity\Permissions;
use App\Entity\View\PermissionsView;
use Exception;

class PermissionsAdapter extends AbstractAdapter
{

    /**
     * @param array|null $data
     * @param int|null $id
     * @return array
     */
    public function getUsersGroup(?array $data, ?int $id = null)
    {
        $data = $this->em->getRepository(PermissionsView::class)->fetch($data, $id);
        return $this->setColumnsOrder("permissions", $data);
    }

    /**
     * @return mixed
     */
    public function mountRoutes(): mixed
    {
        return $this->em->getRepository(Permissions::class)->mountRoutes($this->container->get('router'));
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function save($id, $data): mixed
    {
        if ($id == 1) {
            throw new Exception("Não é possível editar o grupo do super usuario");
        }
        if ($id == 0) {
            $grupo = new UsersGroup();
        } else {
            $grupo = $this->em
                ->getRepository(UsersGroup::class)
                ->find($data["id"]);
        }

        $grupo->setName($data["nome"]);
        $this->em->persist($grupo);

        // save the permissions
        // first clean all
        $this->em->getRepository(Permissions::class)->clean($grupo->getId());


        foreach ($data["permissoes"] as $k => $v) {
            if ($v) {
                $permissao = new Permissions();
                $permissao->setGroup($grupo);
                $permissao->setIsOpen(true);
                $permissao->setRoute($k);
                $this->em->persist($permissao);
            }
        }

        $this->em->flush();

        return $grupo->getId();
    }

    /**
     * @throws Exception
     * @return mixed
     */
    public function getCurrentPermissions(): mixed
    {
        $session = $this->container->get("session");
        if ($this->getUser()->getGroup()->getId() == 1) {
            return $this->mountRoutes();
        }

        $permissoes = $session->get("permissoes");
        $retorno = array();

        /**
         * @var $p Permissions
         */
        foreach ($permissoes as $p) {
            $retorno[$p->getRoute()] = $p->getIsOpen();
        }
        return $retorno;
    }
}