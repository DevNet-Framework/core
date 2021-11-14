<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Dependency;

use IteratorAggregate;

interface IServiceCollection extends IteratorAggregate
{
    public function add(ServiceDescriptor $serviceDescriptor): void;

    public function getIterator(): Iterable;
}
