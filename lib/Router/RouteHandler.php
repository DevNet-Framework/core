<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\Async\Tasks\Task;
use DevNet\System\Dependency\Activator;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Exceptions\PropertyException;

class RouteHandler implements IRouteHandler
{
    private IServiceProvider $serviceProvider;
    private $target;

    public function __get(string $name)
    {
        if ($name == 'Target') {
            return $this->target;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __set(string $name, $value)
    {
        if ($name == 'Target') {
            $this->target = $value;
            return;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . self::class . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . self::class . "::" . $name);
    }

    public function __construct(IServiceProvider $serviceProvider, $target)
    {
        $this->serviceProvider = $serviceProvider;
        $this->target = $target;
    }

    public function handle(RouteContext $routeContext): Task
    {
        $target = null;

        if (is_string($this->target)) {
            $target = $this->target;
        } else if (is_object($this->target)) {
            $handler = $this->target;
        } else {
            throw new RouterException("Invalid Argument Type, route endpoint must be of type callable|string");
        }

        if ($target) {
            $handler = Activator::CreateInstance($target, $this->serviceProvider);
        }

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}
