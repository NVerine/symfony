<?php

namespace App\DataFixtures;

use App\Entity\Reports;
use App\Service\ReportsList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReportFixtures extends Fixture
{
    /**
     * @var array
     */
    private array $reportsList;

    /**
     * ReportFixtures constructor.
     * @param ReportsList $reportsList
     */
    public function __construct(ReportsList $reportsList)
    {
        $this->reportsList = $reportsList->getRepportsList();
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->reportsList as $report) {
            foreach ($report["columns"] as $k => $column) {
                for ($i = 0; $i < 4; $i++) {
                    $item = new Reports();
                    $item->setColumnName($column);
                    $item->setColumnOrder($k + 1);
                    $item->setLevel($i + 1);
                    $item->setName($report["name"]);
                    if (rand(1, 2) % 2 > 0){
                        $faker = Factory::create();
                        $item->setColumnNameReplacer($faker->name);
                    }
                    $manager->persist($item);
                }
            }
        }

        $manager->flush();
        echo "\n50 reports inserted";
    }
}
