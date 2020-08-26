<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Metadata;

class EntityTypeBuilder
{
    private EntityType $Metadata;

    public function __construct(EntityType $entityType)
    {
        $this->Metadata = $entityType;
    }

    public function toTable(string $name)
    {
        $this->Metadata->setTableName($name);
        return $this;
    }

    public function property(string $propertyName)
    {
        $property = $this->Metadata->getProperty($propertyName);

        if ($property)
        {
            return $property;
        }

        throw new \Exception("Property Dose not exist");
    }

    public function hasKey(string $key)
    {
        $this->Metadata->setPrimaryKey($key);
        return $this;
    }

    public function hasForeignKey(string $propertyName, string $entityReference)
    {
        $this->Metadata->addForeignKey($propertyName, $entityReference);
        return $this;
    }

    public function hasMany(string $navigationName, string $EntityReference)
    {
        $navigation = $this->Metadata->getNavigation($navigationName);
        
        if (!$navigation)
        {
            throw new \Exception("Navigation Property {$navigationName} dose not exist or it's not of IList type");
        }

        $navigation->hasMany($EntityReference);
        return $this;
    }

    public function hasOne(string $navigationName, string $entityReference)
    {
        $navigation = $this->Metadata->getNavigation($navigationName);
        
        if (!$navigation)
        {
            throw new \Exception("Navigation Property {$navigationName} dose not exist or it's not of IEntity type");
        }

        $navigation->hasOne($entityReference);
        return $this;
    }
}