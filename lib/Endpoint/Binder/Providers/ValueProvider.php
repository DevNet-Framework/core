<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Binder\Providers;

use DevNet\Core\Endpoint\Binder\IValueProvider;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class ValueProvider implements IValueProvider
{
    protected $values = [];

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @return mixed Finds an entry of the container by its identifier and returns it.
     * Return null if the entry does not exist.
     */
    public function getValue(string $key)
    {
        return $this->values[$key] ?? null;
    }

    /**
     * Check if the container can return an entry for the given identifier.
     * Returns true, otherwise Returns false.
     */
    public function contains(string $key): bool
    {
        return isset($this->values[$key]);
    }
}
