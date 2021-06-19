<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Dependency;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
class ServiceProvider implements IServiceProvider
{
    protected array $InstanceServices = [];
    protected IserviceCollection $ServiceCollection;

    public function __construct(IServiceCollection $serviceCollection)
    {
        $this->ServiceCollection = $serviceCollection;
    }

    /**
     * @param string $id Identifier of the entry to look for.
     * @throws NotFoundException  No entry was found for **this** identifier.
     * @throws ContainerException Error while retrieving the entry.
     * @return mixed Finds an entry of the container by its identifier and returns it.
     */
    public function getService(string $serviceType)
    {
        foreach ($this->ServiceCollection as $serviceDescriptor)
        {
            if ($serviceType == $serviceDescriptor->ServiceType)
            {
                if ($serviceDescriptor->ImplementationInstance)
                {
                    if (isset($this->InstanceServices[$serviceType]))
                    {
                        if ($serviceDescriptor->Lifetime == 1)
                        {
                            return $this->InstanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2)
                        {
                            return clone $this->InstanceServices[$serviceType];
                        }
                    }
                    $this->InstanceServices[$serviceType] = $serviceDescriptor->ImplementationInstance;
                    return $serviceDescriptor->ImplementationInstance;
                }

                if ($serviceDescriptor->ImplimentationType)
                {
                    if (isset($this->InstanceServices[$serviceType]))
                    {
                        if ($serviceDescriptor->Lifetime == 1)
                        {
                            return $this->InstanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2)
                        {
                            return clone $this->InstanceServices[$serviceType];
                        }
                    }
                    
                    $instance = Activator::CreateInstance($serviceDescriptor->ImplimentationType, $this);
                    $this->InstanceServices[$serviceType] = $instance;
                    return $instance;
                }

                if ($serviceDescriptor->ImplimentationFactory)
                {
                    if (isset($this->InstanceServices[$serviceType]))
                    {
                        if ($serviceDescriptor->Lifetime == 1)
                        {
                            return $this->InstanceServices[$serviceType];
                        }

                        if ($serviceDescriptor->Lifetime == 2)
                        {
                            return clone $this->InstanceServices[$serviceType];
                        }
                    }
                    $factory = $serviceDescriptor->ImplimentationFactory;
                    $instance = $factory($this);

                    if (!$instance instanceof $serviceType)
                    {
                        throw new \Exception("Return value of factory function must be of the type '$serviceType'");
                    }

                    $this->InstanceServices[$serviceType] = $instance;
                    return $instance;
                }
            }
        }

        throw new \Exception("service '$serviceType' not found");
    }

    /**
     * @param string $id Identifier of the entry to look for.
     * @return bool Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     */
    public function has(string $serviceType) : bool
    {
        //$service = strtolower($service);

        foreach ($this->ServiceCollection as $serviceDescriptor)
        {
            if ($serviceDescriptor->ServiceType == $serviceType)
            {
                return true;
            }
        }

        return false;
    }
}
