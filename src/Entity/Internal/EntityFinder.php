<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Internal;

use Artister\DevNet\Entity\IEntity;
use Artister\DevNet\Entity\Metadata\EntityType;
use Artister\DevNet\Entity\Metadata\EntityNavigation;
use Artister\DevNet\Entity\Tracking\EntityStateManager;
use Artister\System\Database\DbConnection;
use Artister\System\Collections\Enumerator;

class EntityFinder
{   
    private DbConnection $Connection;
    private EntityMapper $Mapper;
    private EntityStateManager $EntityStateManager;

    public function __construct(EntityMapper $mapper)
    {
        $this->Mapper               = $mapper;
        $this->Connection           = $mapper->Connection;
        $this->EntityStateManager   = $mapper->EntityStateManager;
    }

    public function find(EntityType $entityType, $id)
    {
        $entry = $this->EntityStateManager->getEntry($entityType->getName(), $id);
        if ($entry)
        {
            return $entry->Entity;
        }

        $this->Connection->open();
        $DbCommand = $this->Connection->createCommand("SELECT * FROM {$entityType->getTableName()} WHERE {$entityType->getPrimaryKey()} = ?");
        $DbCommand->addParameters([$id]);

        $dbReader = $DbCommand->executeReader($entityType->getName());
        if ($dbReader)
        {
            $entity = $dbReader->read();
            $dbReader->close();
            
            $this->load($entity);
            return $entity;
        }

        return null;
    }

    public function load(IEntity $entity)
    {
        $this->Mapper->attach($entity);

        $entityType = $this->Mapper->Model->getEntityType(get_class($entity));
        $key = $entityType->PropertyKey;

        foreach ($entityType->Navigations as $navigation)
        {
            $navigation->PropertyInfo->setAccessible(true);
            if ($navigation->NavigationType == 2)
            {
                $navigation->PropertyInfo->setValue($entity, new EntityCollection($navigation, $this->Mapper, $entity->$key));
            }
            else if ($navigation->NavigationType == 1)
            {
                $foreignKey = $navigation->Metadata->getForeignKey($navigation->MetadataReference->getName());
                if ($foreignKey)
                {
                    $ParentEntity = $this->find($navigation->MetadataReference, $entity->$foreignKey);
                    $navigation->PropertyInfo->setValue($entity, $ParentEntity);
                }
                else
                {
                    $childEntity = $this->Query($navigation, $entity->$key)->current();
                    if ($childEntity)
                    {
                        $navigation->PropertyInfo->setValue($entity, $childEntity);
                    }
                }
            }
        }
    }

    public function Query(EntityNavigation $navigation, $keyValue)
    {
        $tableReference = $navigation->MetadataReference->getTableName();
        $foreignKey = $navigation->getForeignKey();

        $this->Connection->open();
        $DbCommand = $this->Connection->createCommand("SELECT * FROM {$tableReference} WHERE {$foreignKey} = ?");
        $DbCommand->addParameters([$keyValue]);
        $dbReader = $DbCommand->executeReader($navigation->MetadataReference->getName());

        $entities = [];
        if ($dbReader) {
            while ($relatedEntity = $dbReader->read()) {
                $this->load($relatedEntity);
                $entities[] = $relatedEntity;
            }
            $dbReader->close();
        }

        return new Enumerator($entities);
    }
}