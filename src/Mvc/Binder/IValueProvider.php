<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc\Binder;

interface IValueProvider
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param string $key Identifier of the entry to look for.
     * @return mixed Entry.
     */
    public function getValue(string $key);

    public function contains(string $key);
}
