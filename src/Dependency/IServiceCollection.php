<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Dependency;

use IteratorAggregate;

interface IServiceCollection extends IteratorAggregate
{
    public function add(ServiceDescriptor $serviceDescriptor) : void;

    public function getIterator() : Iterable;
}