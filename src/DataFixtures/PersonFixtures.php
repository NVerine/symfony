<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\PersonContact;
use App\Entity\PersonAddress;
use App\Util\ValueHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tipoarr = ["F", "J"];

        // create 50 pessoas! Bam!
        for ($i = 0; $i < 50; $i++) {
            $faker = Factory::create();

            $item = new Person();
            $tipo = $tipoarr[array_rand($tipoarr)];
            $item->setType($tipo);
            $item->setName($faker->name);
            $item->setNickname($faker->name);
            $item->setIsActive($faker->boolean);
            $item->setIsCustomer($faker->boolean);
            $item->setIsSupplier($faker->boolean);
            $item->setIsEmployee($faker->boolean);
            $item->setObservations($faker->text);
            $item->setBirthDate($faker->dateTime);

            $manager->persist($item);

            $enderecos = [];
            $contatos = [];
            for ($ii = 0; $ii < 5; $ii++) {
                $enderecos[] = $this->saveAddress($item, $manager);
                $contatos[] = $this->saveContacts($item, $manager);
            }
            $item->setMainContact($contatos[array_rand($contatos)]);
            $item->setMainAddress($enderecos[array_rand($enderecos)]);

            $manager->persist($item);
        }
        echo "\n50 people inserted";
        $manager->flush();
    }

    /**
     * @param Person $pessoa
     * @return PersonAddress
     */
    protected function saveAddress(Person $pessoa, ObjectManager $manager): PersonAddress
    {
        $endereco = Factory::create();
        $item = new PersonAddress();

        $item->setPerson($pessoa);
        $item->setDistrict($endereco->citySuffix);
        $item->setAddress($endereco->streetName);
        $item->setZip(ValueHelper::maskCep($endereco->randomNumber(8)));
        $item->setNumber($endereco->buildingNumber);
        $item->setCity($endereco->city);
        $item->setAddressComplement($endereco->streetAddress);
        $item->setIbgeCidade($endereco->randomNumber(6));
        $item->setUf($endereco->stateAbbr);

        $manager->persist($item);
        return $item;
    }

    /**
     * @param Person $pessoa
     * @return PersonContact
     */
    protected function saveContacts(Person $pessoa, ObjectManager $manager): PersonContact
    {
        $contato = Factory::create();
        $item = new PersonContact();

        $item->setPerson($pessoa);
        $item->setContactName($contato->firstName);
        $item->setEmail($contato->email);
        $item->setPhone($contato->phoneNumber);

        $manager->persist($item);
        return $item;
    }
}
