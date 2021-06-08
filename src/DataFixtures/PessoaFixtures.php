<?php

namespace App\DataFixtures;

use App\Entity\Pessoa;
use App\Entity\PessoaContato;
use App\Entity\PessoaEndereco;
use App\Util\ValueHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PessoaFixtures extends Fixture
{
    protected Generator $faker;
    protected ObjectManager $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $tipoarr = ["F", "J"];

        // create 50 pessoas! Bam!
        for ($i = 0; $i < 50; $i++) {
            $this->faker = Factory::create();

            $item = new Pessoa();
            $tipo = $tipoarr[array_rand($tipoarr)];
            $item->setTipo($tipo);
            $item->setNome($this->faker->name);
            $item->setNomeFantasia($this->faker->name);
            $item->setEmpresa($this->faker->boolean);
            $item->setAtivo($this->faker->boolean);
            $item->setCliente($this->faker->boolean);
            $item->setFornecedor($this->faker->boolean);
            $item->setFuncionario($this->faker->boolean);
            $item->setObservacoes($this->faker->text);
            if ($tipo == "F") {
                $cpf = sprintf($this->faker->randomNumber(5)).sprintf($this->faker->randomNumber(6));
                $item->setCpfCnpj(ValueHelper::maskCPF(sprintf($cpf)));
            }
            else{
                $cnpj = sprintf($this->faker->randomNumber(7)).sprintf($this->faker->randomNumber(7));
                $item->setCpfCnpj(ValueHelper::maskCNPJ(sprintf($cnpj)));
            }
            $item->setCnae($this->faker->randomNumber(6));
            $item->setRg($this->faker->randomNumber(6));
            $item->setDataNascimento($this->faker->dateTime);

            $this->manager->persist($item);

            $enderecos = [];
            $contatos = [];
            for ($ii = 0; $ii < 5; $ii++) {
                $enderecos[] = $this->saveEnderecos($item);
                $contatos[] = $this->saveContatos($item);
            }
            $item->setContatoPrincipal($contatos[array_rand($contatos)]);
            $item->setEnderecoPrincipal($enderecos[array_rand($enderecos)]);

            $this->manager->persist($item);
        }
        echo "\n50 Pessoas cadastradas";
        $this->manager->flush();
    }

    /**
     * @param Pessoa $pessoa
     * @return PessoaEndereco
     */
    protected function saveEnderecos(Pessoa $pessoa): PessoaEndereco
    {
        $endereco = Factory::create();
        $item = new PessoaEndereco();

        $item->setPessoa($pessoa);
        $item->setBairro($endereco->citySuffix);
        $item->setLogradouro($endereco->streetName);
        $item->setCep(ValueHelper::maskCep($endereco->randomNumber(8)));
        $item->setNumero($endereco->buildingNumber);
        $item->setCidade($endereco->city);
        $item->setComplemento($endereco->streetAddress);
        $item->setIbgeCidade($endereco->randomNumber(6));
        $item->setUf($endereco->stateAbbr);

        $this->manager->persist($item);
        return $item;
    }

    /**
     * @param Pessoa $pessoa
     * @return PessoaContato
     */
    protected function saveContatos(Pessoa $pessoa): PessoaContato
    {
        $contato = Factory::create();
        $item = new PessoaContato();

        $item->setPessoa($pessoa);
        $item->setNome($contato->firstName);
        $item->setEmail($contato->email);
        $item->setTelefone($contato->phoneNumber);

        $this->manager->persist($item);
        return $item;
    }
}
