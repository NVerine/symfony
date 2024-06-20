<?php

namespace App\Adapter;

use App\Entity\UsersGroup;
use App\Entity\Permissions;
use App\Entity\View\PermissionsView;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class PermissionsAdapter extends AbstractAdapter
{

    /**
     * @param array|null $data
     * @param int|null $id
     * @return array
     * @throws ExceptionInterface
     */
    public function getUsersGroup(?array $data, ?int $id = null)
    {
        // return the normal content
        if($id !== null){
            $data = $this->em->getRepository(UsersGroup::class)->getOne($id, $data);
            $data[0]->setRoutes($this->mountRoutes());
            return $data;
        }

        $data = $this->em->getRepository(PermissionsView::class)->fetch($data);
        return $this->setColumnsOrder("permissions", $data);
    }

    public function mountRoutes()
    {
        return $this->em->getRepository(Permissions::class)->mountRoutes($this->container->get('router'));
    }

    /**
     * @param $id
     * @param $data
     * @throws Exception
     */
    public function save($id, $data)
    {
        dump($data);
        if ($id == 1) {
            throw new Exception("Não é possível editar o group do super usuario");
        }
        if ($id == 0) {
            $group = new UsersGroup();
        } else {
            $group = $this->em
                ->getRepository(UsersGroup::class)
                ->find($data["id"]);
        }

        $group->setName($data["name"]);
        $this->em->persist($group);

        // first clean all
        $permissions = $this->em->getRepository(Permissions::class)->findBy(array('group' => $group));
        foreach ($permissions as $p) {
            $this->em->remove($p);
        }

        foreach ($data["permissions"] as $k => $v) {
            if ($v) {
                $permissions = new Permissions();
                $permissions->setGroup($group);
                $permissions->setIsOpen(true);
                $permissions->setRoute($k);
                $this->em->persist($permissions);
            }
        }

        $this->em->flush();

        return $group->getId();
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

    function fetch(?array $data, $id = null)
    {
        // TODO: Implement fetch() method.
    }
}