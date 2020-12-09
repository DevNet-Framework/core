<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Entity\Query;

use Artister\System\Collections\Enumerator;
use Artister\System\Linq\Enumerables\TakeEnumerable;
use Artister\System\Compiler\Expressions\Expression;
use Artister\System\Linq\IQueryProvider;
use Artister\System\Linq\IQueryable;

/**
 * create expression from method and passe it to queryProvider.
 */
class EntityQuery implements IQueryable
{
    use \Artister\System\Extension\ExtensionTrait;

    public string $ResultType;
    public IQueryProvider $Provider;
    public Expression $Expression;

    public function __construct(string $resultType, IQueryProvider $provider, Expression $expression = null)
    {
        $this->ResultType   = $resultType;
        $this->Provider     = $provider;
        $this->Expression   = ($expression == null) ? Expression::constant($this) : $expression;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getIterator() : Enumerator
    {
        return $this->Provider->execute($this->ResultType, $this->Expression);
    }

    public function toArray() : array
    {
        return $this->getIterator()->toArray();
    }

    public function __toString()
    {
        return $this->Provider->GetQueryText($this->Expression);
    }

    public function first()
    {
        $array = $this->toArray();
        return reset($array);
    }

    public function last()
    {
        $array = $this->toArray();
        return end($array);
    }

    public function asEnumerable()
    {
        return new TakeEnumerable($this);
    }
}