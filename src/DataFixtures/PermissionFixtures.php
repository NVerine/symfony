<?php

namespace App\DataFixtures;

use App\Entity\UsersGroup;
use App\Entity\Permissions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PermissionFixtures extends Fixture
{

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * PermissionFixtures constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $rotas = $manager->getRepository(Permissions::class)->mountRoutes($this->container->get('router'));

        $i = 0;
        foreach ($rotas as $k=>$v)
        {
            $i++;
            if($i % 2 == 0){
                unset($rotas[$k]);
            }
        }

        for ($i = 0; $i < 50; $i++) {
            $faker = Factory::create();

            //cria o grupo primeiro e depois concede as permissoes
            $grupo = new UsersGroup();
            $grupo->setName($faker->firstName);
            $manager->persist($grupo);

            // agora cria as novas
            foreach ($rotas as $k => $v) {
                $permissao = new Permissions();
                $permissao->setGroup($grupo);
                $permissao->setIsOpen(true);
                $permissao->setRoute($k);
                $manager->persist($permissao);
            }
        }

        echo "\n50 groups with permissions inserted";
        $manager->flush();
    }
}