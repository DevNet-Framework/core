<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Metadata;

use ReflectionProperty;

class EntityNavigation
{   
    const NavigationReference  = 1;
    const NavigationCollection = 2;

    private ReflectionProperty $PropertyInfo;
    private EntityType $Metadata;
    private EntityType $MetadataReference;
    private int $NavigationType = 0;

    public function __construct(EntityType $entityType, ReflectionProperty $propertyInfo)
    {
        $this->Metadata         = $entityType;
        $this->PropertyInfo     = $propertyInfo;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function hasMany(string $EntityReference)
    {
        $this->MetadataReference    = $this->Metadata->Model->getEntityType($EntityReference);
        $this->NavigationType       = 2;
    }

    public function hasOne(string $EntityReference)
    {
        $this->MetadataReference    = $this->Metadata->Model->getEntityType($EntityReference);
        $this->NavigationType       = 1;
    }

    public function getForeignKey() : ?string
    {
        return $this->MetadataReference->getForeignKey($this->Metadata->getName());
    }
}