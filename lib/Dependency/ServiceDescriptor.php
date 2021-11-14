<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Dependency;

use Closure;
use DevNet\System\Exceptions\ClassException;

class ServiceDescriptor
{
    const Singleton = 1;
    const Transient = 2;

    protected int $Lifetime;
    protected string $ServiceType;
    protected ?string $ImplimentationType = null;
    protected ?object $ImplementationInstance = null;
    protected ?Closure $ImplimentationFactory = null;

    /**
     * Read-only for all properties.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(int $lifetime, string $serviceType, $service)
    {
        switch ($service) {
            case is_callable($service):
                $this->describeFactory($lifetime, $serviceType, $service);
                break;
            case is_object($service):
                $this->describeInstance($lifetime, $serviceType, $service);
                break;
            case is_string($service):
                $this->describeType($lifetime, $serviceType, $service);
                break;
            default:
                throw new \Exception("incomplatible type, it must be of type object, or string of class type, or a callable factory");
                break;
        }
    }

    public function describeInstance(int $lifetime, string $serviceType, object $implementationInstance): void
    {
        if (!$implementationInstance instanceof $serviceType) {
            throw new ClassException("The given service: " . get_class($implementationInstance) . " is not compatible with the declared type: {$serviceType}");
        }

        $this->Lifetime               = $lifetime;
        $this->ServiceType            = $serviceType;
        $this->ImplementationInstance = $implementationInstance;
    }

    public function describeType(int $lifetime, string $serviceType, string $implimentationType): void
    {
        if (!class_exists($serviceType) && !interface_exists($serviceType)) {
            throw new ClassException("Can not find declared service type: {$serviceType}");
        }

        if (!class_exists($implimentationType)) {
            throw new ClassException("Can not find declared service type: {$implimentationType}");
        }

        $interfaces = class_implements($implimentationType);
        if (!in_array($serviceType, $interfaces) && $serviceType != $implimentationType) {
            throw new ClassException("The given service: {$implimentationType} is not compatible with the declared type: {$serviceType}");
        }

        $this->Lifetime           = $lifetime;
        $this->ServiceType        = $serviceType;
        $this->ImplimentationType = $implimentationType;
    }

    public function describeFactory(int $lifetime, string $serviceType, Closure $implimentationFactory): void
    {
        if (!class_exists($serviceType) && !interface_exists($serviceType)) {
            throw new ClassException("Can not find declared service type: {$serviceType}");
        }

        $this->Lifetime              = $lifetime;
        $this->ServiceType           = $serviceType;
        $this->ImplimentationFactory = $implimentationFactory;
    }
}
