<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\Async\Task;
use DevNet\System\Dependency\Activator;
use DevNet\System\PropertyTrait;
use DevNet\Web\Action\ActionDelegate;

class RouteHandler implements IRouteHandler
{
    use PropertyTrait;

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
            $handler = Activator::CreateInstance($this->target, $routeContext->HttpContext->RequestServices);
        } else {
            throw new RouterException("Invalid Argument Type, route endpoint must be of type callable|string");
        }

        $handler = new ActionDelegate($handler);

        foreach (array_reverse($this->filters) as $filter) {
            $handler = new ActionDelegate(function ($context) use ($filter, $handler) {
                return $filter($context, $handler);
            });
        }

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}
