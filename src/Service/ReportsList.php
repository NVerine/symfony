<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ReportsList
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * GetRepportsList constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getRepportsList()
    {
        $dir = $this->params->get('kernel.project_dir')."/config/reports/";
        $finder = new Finder();

        // find all files in the current directory
        $finder->files()->in($dir);

        $value = [];
        foreach ($finder as $file) {
            $value[] = Yaml::parseFile($dir.$file->getRelativePathname());
        }

        return $value;
    }
}