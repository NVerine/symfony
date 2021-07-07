<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class NverineTestCase
 * @package App\Tests
 */
abstract class NverineTestCase extends WebTestCase
{
    public $notify;
    public $repository;

    /**
     * quase sempre é feito o override dessa prop
     * @var string
     */
    public string $entity;

    /**
     * cria os injections
     */
    protected function setUp()
    {
        static::bootKernel();
        $this->notify = static::$kernel->getContainer()->get('nverine.notify');
        $this->repository = static::$kernel->getContainer()->get('doctrine')->getRepository($this->entity);
    }
}