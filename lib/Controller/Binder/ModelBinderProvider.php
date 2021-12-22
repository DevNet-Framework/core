<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller\Binder;

use IteratorAggregate;
use ArrayIterator;

class ModelBinderProvider implements IteratorAggregate
{
    private array $ModelBinders = [];

    public function __construct(IModelBinder $modelBinder = null)
    {
        if ($modelBinder) {
            $this->ModelBinders[] = $modelBinder;
        }
    }

    public function add(IModelBinder $modelBinder)
    {
        $this->ModelBinders[] = $modelBinder;
        return $this;
    }

    public function getIterator(): iterable
    {
        return new ArrayIterator($this->ModelBinders);
    }
}
