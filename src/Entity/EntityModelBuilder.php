<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity;

use Artister\DevNet\Entity\Metadata\EntityModel;
use Artister\DevNet\Entity\Metadata\EntityTypeBuilder;
use Artister\DevNet\Entity\Metadata\IEntityTypeConfiguration;

class EntityModelBuilder
{
    private EntityModel $Model;

    public function __construct()
    {
        $this->Model = new EntityModel($this);
    }
    
    public function entity(string $entityName) : EntityTypeBuilder
    {
        $entityType = $this->Model->getEntityType($entityName);

        return new EntityTypeBuilder($entityType);
    }

    public function ApplyConfiguration(IEntityTypeConfiguration $configuration)
    {
        $configuration->configure($this->entity($configuration->getEntityName()));
    }

    public function getModel()
    {
        return $this->Model;
    }
}