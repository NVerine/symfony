<?php


namespace App\DataFixtures;


use App\Controller\Api\PermissoesController;
use App\Entity\GrupoUsuarios;
use App\Entity\Permissoes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PermissionFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var Generator
     */
    protected Generator $faker;

    /**
     * @var ObjectManager
     */
    protected ObjectManager $manager;

    /**
     * @var array
     */
    private array $rotas;

    /**
     * PermissionFixtures constructor.
     * @param PermissoesController $permissoesController
     */
    public function __construct(PermissoesController $permissoesController)
    {
        $this->rotas = $permissoesController->montaRotas();
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['permission'];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $i = 0;
        foreach ($this->rotas as $k=>$v)
        {
            $i++;
            if($i % 2 == 0){
                unset($this->rotas[$k]);
            }
        }

        for ($i = 0; $i < 50; $i++) {
            $this->faker = Factory::create();

            //cria o grupo primeiro e depois concede as permissoes
            $grupo = new GrupoUsuarios();
            $grupo->setNome($this->faker->firstName);
            $this->manager->persist($grupo);

            // agora cria as novas
            foreach ($this->rotas as $k => $v) {
                $permissao = new Permissoes();
                $permissao->setGrupo($grupo);
                $permissao->setLiberado(true);
                $permissao->setRota($k);
                $this->manager->persist($permissao);
            }
        }

        echo "\n50 Grupos com permissões cadastrados";
        $this->manager->flush();
    }
}