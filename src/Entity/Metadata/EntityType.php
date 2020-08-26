<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Metadata;

use Artister\DevNet\Entity\IEntity;
use Artister\System\Collections\IList;
use Reflector;

class EntityType
{
    private EntityModel $Model;
    private string $EntityName;
    private Reflector $EntityInfo;
    private string $TableName;
    private string $PropertyKey  = 'key';
    private array $ForeignKeys  = [];
    private array $Properties   = [];
    private array $Navigations  = [];

    public function __construct(string $entityName, EntityModel $model)
    {
        $this->Model = $model;
        $this->EntityName = $entityName;
        $this->EntityInfo = new \ReflectionClass($entityName);
        $this->TableName = $this->EntityInfo->getShortName();

        $scalarTypes = ['bool', 'int', 'float', 'string'];
        foreach ($this->EntityInfo->getProperties() as $PropertyInfo)
        {
            if ($PropertyInfo->hasType())
            {
                $propertyName = $PropertyInfo->getName();
                $propertyType = $PropertyInfo->getType()->getName();
                if (in_array(strtolower($propertyType), $scalarTypes))
                {
                    $this->Properties[$propertyName] = new EntityProperty($this, $PropertyInfo);
                    if (strtolower($propertyName) == 'id'){
                        $this->PropertyKey = $propertyName;
                    }
                }
                else
                {
                    if ($propertyType == IList::class)
                    {
                        // later add conventional code here
                        $this->Navigations[$propertyName] = new EntityNavigation($this, $PropertyInfo); //new EntityNavigation($this);
                    }
                    else
                    {
                        if (class_exists($propertyType))
                        {
                            $interfaces = class_implements($propertyType);
                            if (in_array(IEntity::class, $interfaces))
                            {
                                // later add conventional code here
                                $this->Navigations[$propertyName] = new EntityNavigation($this, $PropertyInfo); //new EntityNavigation($this);
                            }
                        }
                    }
                }
            }
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getName()
    {
        return $this->EntityName;
    }

    public function getTableName() : string
    {
        return $this->TableName;
    }

    public function getPrimaryKey() : string
    {
        $property = $this->getProperty($this->PropertyKey);
        if ($property)
        {
            return $property->Column['Name'];
        }
    }

    public function getForeignKey(string $entityReference) : ?string
    {
        if (isset($this->ForeignKeys[$entityReference]))
        {
            $propertyName = $this->ForeignKeys[$entityReference];
            $property = $this->getProperty($propertyName);
            return $property->Column['Name'];
        }
        
        return null;
    }

    public function getProperty(string $propertyName)
    {
        return $this->Properties[$propertyName] ?? null;
    }

    public function getNavigation(string $navigationName)
    {
        return $this->Navigations[$navigationName] ?? null;
    }

    public function setTableName(string $name)
    {
        $this->TableName = $name;
    }

    public function setPrimaryKey(string $propertyName)
    {
        $property = $this->getProperty($propertyName);
        if ($property)
        {
            $this->PropertyKey = $propertyName;
        }
    }

    public function addForeignKey(string $propertyName, string $entityReference)
    {
        $this->ForeignKeys[$entityReference] = $propertyName;
    }
}