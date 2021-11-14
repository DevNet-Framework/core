<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\Async\Task;
use DevNet\System\Activator;
use DevNet\System\IServiceProvider;

class RouteHandler implements IRouteHandler
{
    private IServiceProvider $ServiceProvider;
    private $Target;

    public function __construct(IServiceProvider $serviceProvider, $target)
    {

        $this->ServiceProvider = $serviceProvider;
        $this->Target = $target;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }

    public function handle(RouteContext $routeContext): Task
    {
        $target = null;

        if (is_string($this->Target)) {
            $target = $this->Target;
        } else if (is_object($this->Target)) {
            $handler = $this->Target;
        } else {
            throw new RouterException("Invalid Argument Type, route endpoint must be of type callable|string");
        }

        if ($target) {
            $handler = Activator::CreateInstance($target, $this->ServiceProvider);
        }

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}
