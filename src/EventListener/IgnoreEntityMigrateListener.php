<?php

namespace App\EventListener;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Symfony\Component\HttpKernel\KernelEvents;

class IgnoreEntityMigrateListener
{
    private $nameSpaceToIgnore = 'App\Entity\View';

    /**
     * Remove ignored tables /entities from Schema
     *
     * @param GenerateSchemaEventArgs $args
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args)
    {
        $schema = $args->getSchema();
        $em = $args->getEntityManager();
        $entities = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        $ignoredTables = [];
        foreach ($entities as $r){
            $rc = new \ReflectionClass($r);
            if( $rc->getNamespaceName() == $this->nameSpaceToIgnore) {
                $ignoredTables[] = strtolower($schema->getName().".".$em->getClassMetadata($r)->getTableName());
            }
        }

        foreach ($schema->getTableNames() as $tableName) {
            if (in_array($tableName, $ignoredTables)) {
                // remove table from schema
                $schema->dropTable($tableName);
            }
        }
    }
}