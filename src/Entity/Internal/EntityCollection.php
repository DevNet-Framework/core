<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Internal;

use Artister\DevNet\Entity\Metadata\EntityNavigation;
use Artister\System\Collections\Enumerator;
use Artister\System\Collections\IList;

class EntityCollection implements IList
{
    private EntityNavigation $Navigation;
    private EntityMapper $Mapper;
    private $KeyValue;

    public function __construct(
        EntityNavigation $navigation,
        EntityMapper $mapper,
        $keyValue
        )
    {
        $this->Navigation   = $navigation;
        $this->Mapper       = $mapper;
        $this->KeyValue     = $keyValue;
    }

    public function add($entity) : void
    {
        $this->Mapper->add($entity);
    }

    public function remove($entity) : void
    {
        $this->Mapper->remove($entity);
    }

    public function contains($entity) : bool
    {
        foreach ($this as $entity)
        {
            if ($entity == $entity)
            {
                return true;
            }
        }

        return false;
    }

    public function getIterator() : Enumerator
    {
        return $this->Mapper->Finder->query($this->Navigation, $this->KeyValue);
    }

    public function first()
    {
        foreach ($this->getIterator() as $element)
        {
            return $element;
        }
    }

    public function last()
    {
        foreach ($this->getIterator() as $element)
        {
            $last = $element;
        }
        return $last;
    }

    public function toArray() : array
    {
        return $this->getIterator()->toArray();
    }
}