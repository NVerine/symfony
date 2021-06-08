<?php


namespace App\DataFixtures;


use App\Entity\Filial;
use App\Entity\Pessoa;
use App\Repository\PessoaRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilialFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            PessoaFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $pessoa = $manager
            ->getRepository(Pessoa::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.filial', 'filial')
            ->where('p.empresa = :empresa')
            ->setParameter('empresa', true)
            ->andWhere('p.ativo = :ativo')
            ->setParameter('ativo', true)
            ->andWhere('filial.id is null')
            ->getQuery()
            ->getResult();

        for ($i = 0; $i < 5; $i++) {
            $faker = Factory::create();

            $item = new Filial();
            $item->setNome($faker->name);
            $item->setSocio($pessoa[$i]);
            $item->setTimezone('America/Sao_Paulo');
            $item->setRegimeTributario(rand(1, 3));
            $item->setPulaNf(rand(0, 1000));
            $manager->persist($item);
        }

        echo "\n5 filiais cadastradas";
        $manager->flush();
    }
}