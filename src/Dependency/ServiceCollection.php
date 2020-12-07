<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Dependency;

use ArrayIterator;

class ServiceCollection implements IServiceCollection
{
    use \Artister\System\Extension\ExtensionTrait;

    private array $Services = [];

    public function add(ServiceDescriptor $serviceDescriptor) : void
    {
        $this->Services[] =  $serviceDescriptor;
    }

    public function addSingleton(string $serviceType, $service = null)
    {
        if (!$service) {
            $service = $serviceType;
        } 

        $this->add(new ServiceDescriptor(1, $serviceType, $service));
    }

    public function AddTransient(string $serviceType, $service = null)
    {
        if (!$service) {
            $service = $serviceType;
        }

        $this->add(new ServiceDescriptor(2, $serviceType, $service));
    }

    public function getIterator() : Iterable
    {
        return new ArrayIterator($this->Services);
    }

    public function clear()
    {
        $this->Services = [];
    }
}