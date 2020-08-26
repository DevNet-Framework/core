<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity;

use Artister\DevNet\Entity\Internal\EntityMapper;
use Artister\DevNet\Entity\Metadata\EntityModel;
use Artister\System\Database\DbConnection;
use Artister\System\Database\DbTransaction;

class EntityContext
{
    private ?DbTransaction $Transaction;
    private EntityMapper $Mapper;
    private EntityModel $Model;
    private array $Repositories = [];

    public function __construct(DbConnection $connection)
    {
        $builder        = new EntityModelBuilder();
        $this->Mapper   = new EntityMapper($connection, $builder->getModel());
        $this->Model    = $this->Mapper->Model;
        
        $this->onModelCreate($builder);
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function beginTransaction()
    {
        $this->Transaction = $this->Mapper->Connection->beginTransaction();
    }

    /** Registry pattern and singleton pattern. */
    public function set(string $entityType)
    {
        if (isset($this->Repositories[$entityType]))
        {
            return $this->Repositories[$entityType];
        }

        $entityRepository = new EntitySet($entityType, $this->Mapper);

        $this->Repositories[$entityType] = $entityRepository;
        return $this->Repositories[$entityType];
    }

    public function save()
    {
        return $this->Mapper->save();
    }

    public function commit()
    {
        $this->Transaction->commit();
    }

    public function rollBack()
    {
        $this->Transaction->rollBack();
    }

    public function onModelCreate(EntityModelBuilder $builder)
    {
        # overide code...
    }
}