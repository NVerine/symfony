<?php

namespace App\Adapter;

use App\Entity\Branch;
use App\Entity\Person;
use Exception;

class BranchAdapter extends AbstractAdapter
{
    /**
     * @param array|null $data
     * @param null $id
     * @return mixed
     */
    public function fetch(?array $data, $id = null)
    {
        return $this->em->getRepository(Branch::class)->fetch($data, $id);
    }

    public function save($id, $data): mixed
    {
        if (!empty($data["pessoa"]["id"])){
            $pessoa = $this->em
                ->getRepository(Person::class)
                ->findOneBy(["id" => $data["pessoa"]["id"]]);
        }

        if (!empty($id)) {
            $item = $this->em
                ->getRepository(Branch::class)
                ->find($id);
        }
        else {
            $item = new Branch();

            if(!empty($pessoa->getFilial())){
                $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada ja está vinculada à outra filial");
                return $this->notifyReturn("");
            }
        }

        if(empty($pessoa)){
            $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada não é do tipo empresa");
            return $this->notifyReturn("");
        }

        if(empty($pessoa->getContatoPrincipal())){
            $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada não é possui um contato principal");
            return $this->notifyReturn("");
        }

        if(empty($pessoa->getEnderecoPrincipal())){
            $this->notify->addMessage($this->notify::TIPO_ERROR, "Pessoa selecionada não é possui um endereço principal");
            return $this->notifyReturn("");
        }

        $item->setOwner($pessoa);
        $item->setName($data["nome"]);
        $item->setRegimeTributario($data["regimeTributario"]);
        $item->setTimezone($data["timezone"]);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new Exception($errors);
        }

        $this->em->persist($item);
        $this->em->flush();

        return $item->getId();
    }
}