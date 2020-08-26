<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Metadata;

use ReflectionProperty;

class EntityProperty
{   
    private EntityType $Metadata;
    private ReflectionProperty $PropertyInfo;
    private string $TableReference;
    private array $Column = [];
    private ?EntityNavigation $Navigation = null;

    public function __construct(EntityType $entityType, ReflectionProperty $propertyInfo)
    {
        $this->Metadata = $entityType;
        $this->PropertyInfo = $propertyInfo;
        $this->Column['Name'] = $propertyInfo->getName();
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function hasColumn(string $name, string $type = null, int $lenth = null)
    {
        $this->Column['Name'] = $name;
        $this->Column['Type'] = $type;
        $this->Column['Lenth'] = $lenth;
    }

    public function getColumnName() : string
    {
        return $this->Column['Name'];
    }
}