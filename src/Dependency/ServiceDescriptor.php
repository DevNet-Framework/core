<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Dependency;

use Closure;

class ServiceDescriptor
{
    const Singleton = 1;
    const Transient = 2;

    protected int $Lifetime;
    protected string $ServiceType;
    protected ?string $ImplimentationType = null;
    protected ?object $ImplementationInstance = null;
    protected ?Closure $ImplimentationFactory = null;

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
                throw new \Exception("type not complatible");
                break;
        }
    }

    /**
     * Read-only for all properties.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function describeInstance(int $lifetime, string $serviceType, object $implementationInstance)
    {
        if (!$implementationInstance instanceof $serviceType)
        {
            throw new \Exception("type not complatible");
        }

        $this->Lifetime                 = $lifetime;
        $this->ServiceType              = $serviceType;
        $this->ImplementationInstance   = $implementationInstance;
    }

    public function describeType(int $lifetime, string $serviceType, string $implimentationType)
    {
        if (!class_exists($implimentationType))
        {
            throw new \Exception("class not found");
        }

        if (!class_exists($serviceType) && !interface_exists($serviceType))
        {
            throw new \Exception("invalid service type");
        }

        $interfaces = class_implements($implimentationType);
        if (!in_array($serviceType, $interfaces) && $serviceType != $implimentationType)
        {
            throw new \Exception("incompatible type");
        }

        $this->Lifetime             = $lifetime;
        $this->ServiceType          = $serviceType;
        $this->ImplimentationType   = $implimentationType;
    }

    public function describeFactory(int $lifetime, string $serviceType, Closure $implimentationFactory)
    {
        if (!class_exists($serviceType) && !interface_exists($serviceType))
        {
            throw new \Exception("service type not found");
        }

        $this->Lifetime                 = $lifetime;
        $this->ServiceType              = $serviceType;
        $this->ImplimentationFactory    = $implimentationFactory;
    }
}