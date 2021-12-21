<?php

namespace App\DataFixtures;

use App\Entity\Branch;
use App\Entity\Person;
use App\Entity\User;
use App\Entity\UsersGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }

    public function getDependencies(): array
    {
        return [
            PersonFixtures::class,
            BranchFixtures::class,
            PermissionFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $branch = $manager->getRepository(Branch::class)->findAll();
        $person = $manager->getRepository(Person::class)->findAll();
        $group = $manager->getRepository(UsersGroup::class)->findAll();

        // create 10 users! Bam!
        for ($i = 0; $i < 10; $i++) {
            $faker = Factory::create();

            $item = new User();
            $item->setActiveBranch($branch[rand(0,4)]);
            $item->setUsername($faker->userName);
            $item->setPassword(
                $this->encoder->encodePassword(
                    $item,
                    $faker->password
                )
            );

            $item->setGroup($group[rand(0,9)]);
            $item->setPerson($person[rand(0,49)]);

            $manager->persist($item);
            $manager->persist($item);
        }
        echo "\n10 users inserted";
        $manager->flush();
    }
}