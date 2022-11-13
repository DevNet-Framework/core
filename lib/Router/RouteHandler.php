<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\Tasks\Task;
use DevNet\System\Dependency\Activator;
use DevNet\System\ObjectTrait;
use DevNet\Web\Middleware\RequestDelegate;

class RouteHandler implements IRouteHandler
{
    use ObjectTrait;

    private $target;
    private array $filters;

    public function __construct($target, array $filters)
    {
        $this->target = $target;
        $this->filters = $filters;
    }

    public function get_Target()
    {
        return $this->target;
    }

    public function set_Target($value)
    {
        $this->target = $value;
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
