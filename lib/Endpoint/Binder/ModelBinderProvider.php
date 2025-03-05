<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Binder;

use IteratorAggregate;
use ArrayIterator;
use Traversable;

class ModelBinderProvider implements IteratorAggregate
{
    private array $modelBinders = [];

    public function __construct(?IModelBinder $modelBinder = null)
    {
        if ($modelBinder) {
            $this->modelBinders[] = $modelBinder;
        }
    }

    public function add(IModelBinder $modelBinder)
    {
        $this->modelBinders[] = $modelBinder;
        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->modelBinders);
    }
}
