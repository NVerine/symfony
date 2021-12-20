<?php

namespace App\DataFixtures;

use App\Entity\Branch;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BranchFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            PersonFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $pessoa = $manager
            ->getRepository(Person::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.branch', 'branch')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->andWhere('branch.id is null')
            ->getQuery()
            ->getResult();

        for ($i = 0; $i < 5; $i++) {
            $faker = Factory::create();

            $item = new Branch();
            $item->setName($faker->name);
            $item->setOwner($pessoa[$i]);
            $item->setTimezone('America/Sao_Paulo');
            $manager->persist($item);
        }

        echo "\n5 branch inserted";
        $manager->flush();
    }
}