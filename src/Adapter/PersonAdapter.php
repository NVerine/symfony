<?php

namespace App\Adapter;

use App\Entity\Person;
use App\Entity\PersonAddress;
use App\Entity\PersonContact;
use App\Entity\View\PersonView;
use App\Util\ValueHelper;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class PersonAdapter extends AbstractAdapter
{
    /**
     * @param array|null $data
     * @param null $id
     * @return array
     * @throws ExceptionInterface
     */
    public function fetch(?array $data, $id = null)
    {
        // return the normal content
        if($id !== null){
            $data = $this->em->getRepository(Person::class)->getOne($id, $data);
            return $data;
        }
        $data = $this->em->getRepository(PersonView::class)->fetch($data, $id);
        return $this->setColumnsOrder("person", $data);
    }

    public function autocomplete(array $data): array
    {
        $person = $this->fetch($data);

        $temp = array();
        /**
         * @var $p Person
         */
        foreach ($person as $p) {
            $arr = array();
            $arr["text"] = $p->getId() . " | " . " | " . substr($p->getContactName(), 0, 30);
            $arr["value"] = $p->getId();
            $arr["label"] = $p->getContactName();
            $temp[] = $arr;
        }

        return $temp;
    }

    /**
     * @param $id
     * @param $data
     * @return int|mixed|object|null
     */
    public function save($id, $data)
    {
        if ($id > 0) {
            $pessoa = $this->em
                ->getRepository(Person::class)
                ->find($id);
        } else {
            $pessoa = new Person();
        }

        //binarios
        $pessoa->setIsActive(ValueHelper::toBinary($data["is_active"]));
        $pessoa->setIsCustomer(ValueHelper::toBinary($data["is_customer"]));
        $pessoa->setIsSupplier(ValueHelper::toBinary($data["is_supplier"]));
        $pessoa->setIsEmployee(ValueHelper::toBinary($data["is_employee"]));

        //demais
        $pessoa->setName($data["name"]);
        $pessoa->setType($data["type"]);
//        $pessoa->setCpfCnpj($data["cpfCnpj"]);

//        if (!empty($data["birth_date"])) {
//            $date = \DateTime::createFromFormat('d-m-Y', $data["dataNascimento"]);
//            $pessoa->setBirthDate($date);
//        }
        $pessoa->setNickname($data["nickname"]);
        $pessoa->setObservations($data["observations"]);

        $this->em->persist($pessoa);

        if (!empty($data["contact"])) {
            foreach ($data["contact"] as $k => $c) {
                if (!empty($c["id"])) {
                    $contato = $this->em
                        ->getRepository(PersonContact::class)
                        ->find($c["id"]);
                } else {
                    $contato = new PersonContact();
                }

                if (isset($c["exclude"]) && $c["exclude"]) {
                    $this->em->remove($contato);
                } else {
                    $contato->setPerson($pessoa);
                    $contato->setContactName($c["contact_name"]);
                    $contato->setPhone($c["phone"]);
                    $contato->setEmail($c["email"]);
                    $this->em->persist($contato);
//                    if ($data["contatoPrincipal"] == $k) {
//                        $pessoa->setMainContact($contato);
//                    }
                }
            }
        }

        if (!empty($data["address"])) {
            foreach ($data["address"] as $k => $e) {
                if (!empty($e["id"])) {
                    $endereco = $this->em
                        ->getRepository(PersonAddress::class)
                        ->find($e["id"]);
                } else {
                    $endereco = new PersonAddress();
                }

                if (isset($e["exclude"]) && $e["exclude"]) {
                    $this->em->remove($endereco);
                } else {
                    $endereco->setPerson($pessoa);
                    $endereco->setUf($e["uf"]);
                    $endereco->setCity($e["city"]);
                    $endereco->setAddress($e["address"]);
                    $endereco->setDistrict($e["district"]);
                    $endereco->setAddressComplement($e["address_complement"]);
                    $endereco->setNumber($e["number"]);
                    $endereco->setZip($e["zip"]);
                    $this->em->persist($endereco);
//                    if ($data["enderecoPrincipal"] == $k) {
//                        $pessoa->setMainAddress($endereco);
//                    }
                }
            }
        }

        $this->em->persist($pessoa);

        $this->em->flush();
        return $pessoa->getId();
    }
}