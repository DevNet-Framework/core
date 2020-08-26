<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Metadata;

use Artister\DevNet\Entity\EntityModelBuilder;

class EntityModel
{
    private EntityModelBuilder $Builder;
    private array $EntityModel = [];

    public function __construct(EntityModelBuilder $builder)
    {
        $this->Builder = $builder;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function addEntityType(EntityType $entityType)
    {
        $this->EntityModel[$entityType->getName()] = $entityType;
    }

    public function getEntityType(string $entityName)
    {
        if (isset($this->EntityModel[$entityName]))
        {
            return $this->EntityModel[$entityName];
        }

        $entityType = new EntityType($entityName, $this);
        $this->EntityModel[$entityName] = $entityType;

        return $entityType;
    }
}