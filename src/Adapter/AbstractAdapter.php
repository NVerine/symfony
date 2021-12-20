<?php

namespace App\Adapter;

use App\Entity\Reports;
use App\Entity\User;
use App\Service\Notify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractAdapter implements AdapterInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var string
     */
    protected string $defaultEntity;

    /**
     * @var null|ContainerInterface
     */
    protected ?ContainerInterface $container;

    /**
     * @var Notify|null
     */
    protected ?Notify $notify;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->notify = $this->container->get('notify');
    }

    /**
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }
        if (!\is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }
        return $user;
    }

    /**
     * @param array|null $data
     * @param null $id
     * @return mixed
     */
    public function fetch(?array $data, $id = null)
    {
        if (!empty($this->defaultEntity)) {
            return $this->em->getRepository($this->defaultEntity)->fetch($data, $id);
        }
    }

    /**
     * @param string $reportName
     * @param array $items
     * @return array
     * @throws ExceptionInterface
     */
    protected function setColumnsOrder(string $reportName, array $items)
    {
        $columns = $this->em->getRepository(Reports::class)->getColumnsOrder($reportName);
        if (empty($columns)) {
            return $items;
        }

        // weird
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($items, null);
        //

        $return = [];
        foreach ($data as $r) {

            $arr = [];
            foreach ($columns as $column) {
                // never replaces id columns identifier
                if (strtolower($column->getColumnName()) == 'id') {
                    $arr[$column->getColumnName()] = $r[$column->getColumnName()];
                } elseif ($column->getColumnNameReplacer()) {
                    $arr[$column->getColumnNameReplacer()] = $r[$column->getColumnName()];
                } else {
                    $arr[$column->getColumnName()] = $r[$column->getColumnName()];
                }
            }
            $return[] = $arr;
        }
        return $return;
    }
}