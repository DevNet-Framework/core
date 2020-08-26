<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Tracking;

use Artister\DevNet\Entity\IEntity;
use Artister\DevNet\Entity\Metadata\EntityModel;

class EntityStateManager
{
    private EntityModel $Model;
    public array $IdentityMap = [];

    public function __construct(EntityModel $model)
    {
        $this->Model = $model;
    }

    public function getOrCreateEntry(IEntity $entity)
    {
        $entityName = get_class($entity);
        $entityType = $this->Model->getEntityType($entityName);
        $entry = $this->getEntry($entity);
        if ($entry)
        {
            return $entry;
        }

        $entry = new EntityEntry($entity, $entityType);
        $this->addEntry($entry);
        return $entry;
    }

    public function addEntry(EntityEntry $entry)
    {
        $entity = $entry->Entity;
        $entityHash = spl_object_hash($entity);
        $entityName = $entry->Metadata->getName();
        $this->IdentityMap[$entityName][$entityHash] = $entry;
    }

    public function getEntry($entity, int $id = null)
    {
        if (is_string($entity))
        {
            if (isset($this->IdentityMap[$entity]))
            {
                foreach ($this->IdentityMap[$entity] as $entry)
                {
                    $key = $entry->Metadata->PropertyKey;
                    if ($entry->Entity->$key == $id)
                    {
                        return $entry;
                    }
                }
            }
        }

        if ($entity instanceof IEntity)
        {
            $entityName = get_class($entity);
            $entityHash = spl_object_hash($entity);
            if (isset($this->IdentityMap[$entityName][$entityHash]))
            {
                return $this->IdentityMap[$entityName][$entityHash];
            }
        }

        return null;
    }

    public function getEntries() : array
    {
        return $this->IdentityMap;
    }

    public function clearEntries()
    {
        $this->IdentityMap = [];
    }
}