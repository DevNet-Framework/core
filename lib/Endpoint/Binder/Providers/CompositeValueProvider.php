<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Binder\Providers;

use DevNet\Core\Endpoint\Binder\IValueProvider;

class CompositeValueProvider implements IValueProvider
{
    private array $providers = [];

    public function add(IValueProvider $provider)
    {
        $this->providers[get_class($provider)] = $provider;
    }

    /**
     * @return mixed Finds an entry of the container by its identifier and returns it.
     */
    public function getValue(string $key)
    {
        foreach ($this->providers as $provider) {
            if ($provider->contains($key)) {
                return $provider->getValue($key);
            }
        }

        throw new \Exception("No entry was found");
    }

    /**
     * Check if the container can return an entry for the given identifier.
     * Returns true, otherwise Returns false.
     */
    public function contains(string $key): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->contains($key)) {
                return true;
            }
        }

        return false;
    }
}
