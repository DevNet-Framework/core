<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Internal;

use Artister\DevNet\Entity\IEntity;
use Artister\DevNet\Entity\Query\EntityQueryProvider;
use Artister\DevNet\Entity\Tracking\EntityStateManager;
use Artister\DevNet\Entity\Metadata\EntityModel;
use Artister\DevNet\Entity\Tracking\EntityState;
use Artister\System\Database\DbConnection;

class EntityMapper
{
    protected DbConnection $Connection;
    protected EntityModel $Model;
    protected EntityQueryProvider $Provider;
    protected EntityStateManager $EntityStateManager;
    protected EntityFinder $Finder;
    protected EntityPersister $EntityPersister;

    public function __construct(DbConnection $connection, EntityModel $model)
    {
        $this->Connection               = $connection;
        $this->Model                    = $model;
        $this->Provider                 = new EntityQueryProvider($this);
        $this->EntityStateManager       = new EntityStateManager($this->Model);
        $this->Finder                   = new EntityFinder($this);
        $this->EntityPersister          = new EntityPersister($connection);
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function finder(string $entityName)
    {
        $entityType = $this->Model->getEntityType($entityName);
        return $this->EntityFinderFactory->create($entityType);
    }

    public function entry(IEntity $entity)
    {
        return $this->EntityStateManager->getOrCreateEntry($entity);
    }

    public function attach(IEntity $entity)
    {
        $this->entry($entity);
    }

    public function add(IEntity $entity)
    {
        $this->entry($entity)->State = EntityState::Added;
    }
    
    public function remove(IEntity $entity)
    {
        $this->entry($entity)->State = EntityState::Deleted;
    }
    
    public function save()
    {
        $entries = $this->EntityStateManager->getEntries();
        $this->persiste($entries);
        $this->EntityStateManager->clearEntries();
    }

    public function persiste($entries)
    {
        $this->Connection->open();
        foreach ($entries as $entityType)
        {
            foreach ($entityType as $entry)
            {
                $entry->detectChanges();
                switch ($entry->State)
                {
                    case EntityState::Added:
                        $this->EntityPersister->insert($entry);
                        break;
                    case EntityState::Modified:
                        $this->EntityPersister->update($entry);
                        break;
                    case EntityState::Deleted:
                        $this->EntityPersister->delete($entry);
                        break;
                }
            }
        }

        $this->Connection->close();
    }
}