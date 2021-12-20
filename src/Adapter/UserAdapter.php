<?php

namespace App\Adapter;

use App\Entity\Branch;
use App\Entity\Person;
use App\Entity\PersonContact;
use App\Entity\User;
use App\Entity\UsersGroup;
use App\Entity\UserTokens;
use App\Exception\BusinessException;

class UserAdapter extends AbstractAdapter
{
    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws BusinessException
     */
    public function save($id, $data): mixed
    {
//        $passwordEncoder = $this->container->get("user")
        if (($data["grupo"] == 1 && $id != 1) || ($id == 1 && $data["grupo"] != 1)) {
            throw new BusinessException("Only the Admin must and needs to stay at Super-administrator Group");
        }

        $grupo = $this->em
            ->getRepository(UsersGroup::class)
            ->find($data["grupo"]);

        if ($id > 0) {
            $user = $this->em
                ->getRepository(User::class)
                ->find($id);
        } else {
            $user = new User();

            $username_existe = $this->em
                ->getRepository(User::class)
                ->findBy(['username' => $data["username"]]);

            if (!empty($username_existe)) {
                throw new BusinessException("this username is already in use");
            }

            if (empty($data["senha"]) || empty($data["senha2"])) {
                throw new BusinessException("you must fill all passwords fields");
            }

            if (!$data["pessoa_existe"]) {
                $pessoa = new Person();
                $pessoa->setType('F');
                $pessoa->setName($data["pessoa"]["nome"]);
                $pessoa->setNickname($data["pessoa"]["nome"]);
                $this->em->persist($pessoa);

                $contato = new PersonContact();
                $contato->setContactName("Usuário");
                $contato->setPerson($pessoa);
                $contato->setEmail($data["pessoa"]["email"]);
                $contato->setPhone($data["pessoa"]["telefone"]);
                $this->em->persist($contato);
            }
        }

        if (empty($pessoa)) {
            $pessoa = $this->em
                ->getRepository(Person::class)
                ->find($data["pessoa"]["id"]);
        }

        $user->setPerson($pessoa);

        $this->setPassword($user, $data["senha"], $data["senha2"]);

        $user->setUsername($data["username"]);
        $user->setGroup($grupo);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->em->persist($user);

        // salva os vinculos com filial
        if (!empty($data["filiais"])) {
            foreach ($data["filiais"] as $f) {
                //primeiro exclui
                $filial_vinculada = $this->em
                    ->getRepository(Branch::class)
                    ->find($f["id"]);

                if (isset($f["exclui"]) && $f["exclui"]) {
                    $user->removeFiliais($filial_vinculada);
                    $filial_vinculada->removeUser($user);
                } else {
                    $user->addFilais($filial_vinculada);
                    $filial_vinculada->addUser($user);
                }
                $this->em->persist($user);
            }
        }

        // actually executes the queries (i.e. the INSERT query)
        $this->em->flush();

        return $user->getId();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws BusinessException
     */
    public function saveAccount($id, $data)
    {
        $notify = $this->container->get("notify");
        $user = $this->em
            ->getRepository(User::class)
            ->find($id);

        $this->setPassword($user, $data["senha"], $data["senha2"]);

        $filial_ativa = $this->em->getRepository(Branch::class)->find($data["filialAtiva"]["id"]);
        $user->setFilialAtiva($filial_ativa);
        $this->em->persist($user);
        $this->em->flush();

        $notify->addMessage($notify::SUCCESS, "Success saving user");
        return $user->getId();
    }

    /**
     * @throws BusinessException
     */
    private function setPassword(User $user, string $password1 = null, string $password2 = null)
    {
        if (isset($password1)) {
            if ($password1 != $password2) {
                throw new BusinessException("the password don't match");
            }
            // atualiza a senha
            if (!empty($password1)) {
                $passwordEncoder = $this->container->get("security.user_password_encoder.generic");
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $password1
                    )
                );
            }
        }
    }

    /**
     * @param array $data
     * @param array $headers
     * @return array
     * @throws BusinessException
     */
    public function login(array $data, array $headers): array
    {
        $encoder = $this->container->get("security.user_password_encoder.generic");

        $user = $this->em
            ->getRepository(User::class)
            ->login($data["username"]);

        if (empty($user) || !$encoder->isPasswordValid($user, $data["password"])) {
            throw new BusinessException("User or password not found");
        }

        $date = new \DateTime();

        // save login token
        $random = bin2hex(random_bytes(60));

        $token = new UserTokens();
        $token->setUser($user);
        $token->setDate($date);
        $token->setOrigin($headers["origin"][0]);
        $token->setUserAgent($headers["user-agent"][0]);
        $token->setToken($random);
        $token->setIsActive(true);

        $this->em->persist($token);
        $this->em->flush();

        $return["token"] = $random;
        $return["date"] = $date->format('d-m-Y H:i:s');
        $return["username"] = $user->getUsername();
        $return["user_id"] = $user->getId();
        $return["name"] = $user->getPerson()->getName();
        $return["group"] = $user->getGroup()->getName();
        $return["photo"] = "ablab";
        $return["branch_id"] = $user->getActiveBranch()->getId();
        $return["branch_name"] = $user->getActiveBranch()->getName();

        return $return;
    }

    /**
     * @param $data
     * @return string
     */
    public function logout($data)
    {
        $token = $this->em
            ->getRepository(UserTokens::class)
            ->findOneBy(array("token" => $data["token"]));

        /**
         * @var $token UserTokens
         */
        $token->setIsActive(false);
        $this->em->persist($token);
        $this->em->flush();

        return "success";
    }
}