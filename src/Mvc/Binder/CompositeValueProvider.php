<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc\Binder;

class CompositeValueProvider implements IValueProvider
{
    protected $providers = [];

    public function add(IValueProvider $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @return mixed Finds an entry of the container by its identifier and returns it.
     */
    public function getValue(string $key)
    {
        foreach ($this->providers as $provider)
        {
            if ($provider->contains($key))
            {
                return $provider->getValue($key);
            }
        }

        throw new \Exception("No entry was found");
    }

    /**
     * Check if the container can return an entry for the given identifier.
     * Returns true, otherwise Returns false.
     */
    public function contains(string $key) : bool
    {
        foreach ($this->providers as $provider)
        {
            if ($provider->contains($key))
            {
                return true;
            }
        }

        return false;
    }
}