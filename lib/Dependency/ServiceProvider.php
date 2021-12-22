<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Dependency;

use DevNet\System\Activator;
use DevNet\System\IServiceProvider;

class ServiceProvider implements IServiceProvider
{
    protected array $InstanceServices = [];
    protected IserviceCollection $ServiceCollection;

    public function __construct(IServiceCollection $serviceCollection)
    {
        $this->ServiceCollection = $serviceCollection;
    }

    /**
     * Find and return entry of the provider by its service type or null if not found.
     */
    public function getService(string $serviceType): ?object
    {
        foreach ($this->ServiceCollection as $serviceDescriptor) {
            if ($serviceType == $serviceDescriptor->ServiceType) {
                if ($serviceDescriptor->ImplementationInstance) {
                    if (isset($this->InstanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->InstanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->InstanceServices[$serviceType];
                        }
                    }
                    $this->InstanceServices[$serviceType] = $serviceDescriptor->ImplementationInstance;
                    return $serviceDescriptor->ImplementationInstance;
                }

                if ($serviceDescriptor->ImplimentationType) {
                    if (isset($this->InstanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->InstanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->InstanceServices[$serviceType];
                        }
                    }

                    $instance = Activator::CreateInstance($serviceDescriptor->ImplimentationType, $this);
                    $this->InstanceServices[$serviceType] = $instance;
                    return $instance;
                }

                if ($serviceDescriptor->ImplimentationFactory) {
                    if (isset($this->InstanceServices[$serviceType])) {
                        if ($serviceDescriptor->Lifetime == 1) {
                            return $this->InstanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2) {
                            return clone $this->InstanceServices[$serviceType];
                        }
                    }
                    $factory = $serviceDescriptor->ImplimentationFactory;
                    $instance = $factory($this);

                    if (!$instance instanceof $serviceType) {
                        throw new \Exception("Return value of factory function must be of the type '$serviceType'");
                    }

                    $this->InstanceServices[$serviceType] = $instance;
                    return $instance;
                }
            }
        }

        return null;
    }

    /**
     * Check if the provider can return an entry for the given serviceType.
     */
    public function contains(string $serviceType): bool
    {
        if (isset($this->InstanceServices[$serviceType])) {
            return true;
        } else {
            $service = $this->getService($serviceType);
            if ($service) {
                return true;
            }
        }

        return false;
    }
}
