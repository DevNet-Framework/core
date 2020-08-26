<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Tracking;

use Artister\DevNet\Entity\IEntity;
use Artister\DevNet\Entity\Metadata\EntityType;
use Artister\System\Exceptions\PropertyException;

class EntityEntry
{
    private IEntity $Entity;
    private EntityType $Metadata;
    private int $State;
    private array $Values = [];

    public function __construct(IEntity $entity, EntityType $entityType)
    {
        $this->Entity   = $entity;
        $this->Metadata = $entityType;
        $this->State    = EntityState::Attached;
    }

    public function __get(string $name)
    {
        if ($name == "Values")
        {
            foreach ($this->Metadata->Properties as $property)
            {
                $propertyName = $property->PropertyInfo->getName();
                $property->PropertyInfo->setAccessible(true);
                if ($property->PropertyInfo->isInitialized($this->Entity))
                {
                    $this->Values[$propertyName] = $property->PropertyInfo->getValue($this->Entity);
                }
                else
                {
                    $this->Values[$propertyName] = null;
                }
            }
        }

        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        switch ($name)
        {
            case 'entity':
            case 'metadata':
            case 'values':
            case 'navigations':
            case 'references':
                throw PropertyException::privateProperty(self::class, $name);
                break;
        }
        
        $this->$name = $value;
    }

    public function detectChanges()
    {
        $values = [];
        foreach ($this->Metadata->Properties as $property)
        {
            $propertyName = $property->PropertyInfo->getName();
            if (isset($this->Entity->$propertyName))
            {
                $values[$propertyName] = $this->Entity->$propertyName;
            }
            else
            {
                $this->Values[$propertyName] = null;
            }
        }

        if ($this->Values != $values && $this->State == EntityState::Attached)
        {
            $this->State = EntityState::Modified;
            $this->Values = $values;
        }
    }
}