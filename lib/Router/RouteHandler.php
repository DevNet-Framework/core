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
use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Middleware\RequestDelegate;

class RouteHandler implements IRouteHandler
{
    private $target;
    private array $filters;

    public function __get(string $name)
    {
        if ($name == 'Target') {
            return $this->target;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __set(string $name, $value)
    {
        if ($name == 'Target') {
            $this->target = $value;
            return;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . self::class . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . self::class . "::" . $name);
    }

    public function __construct($target, array $filters)
    {
        $this->target = $target;
        $this->filters = $filters;
    }

    public function handle(RouteContext $routeContext): Task
    {
        if (is_callable($this->target)) {
            $handler = $this->target;
        } else if (is_string($this->target)) {
            $handler = Activator::CreateInstance($this->target, $routeContext->httpContext->serviceProvider);
        } else {
            throw new RouterException("Invalid Argument Type, route endpoint must be of type callable|string");
        }

        $handler = new RequestDelegate($handler);

        foreach (array_reverse($this->filters) as $filter) {
            $handler = new RequestDelegate(function ($context) use ($filter, $handler) {
                return $filter($context, $handler);
            });
        }

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}
