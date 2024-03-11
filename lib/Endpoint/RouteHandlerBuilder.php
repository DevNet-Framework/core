<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\Exceptions\ClassException;
use DevNet\Web\Http\Middleware\IRequestHandler;
use DevNet\Web\Http\Middleware\RequestDelegate;
use DevNet\Web\Routing\IRouteHandler;
use DevNet\Web\Routing\RouteHandler;
use ReflectionClass;

class RouteHandlerBuilder
{
    private ActionDelegate $action;
    private array $filters = [];

    public function __construct(IRequestHandler|RequestDelegate $target)
    {
        $this->action = new ActionDelegate(function ($context) use ($target) {
            return $target($context->HttpContext);
        });
    }

    public function addFilter(callable|string $filter, ...$args): static
    {
        if (is_string($filter)) {
            if (!class_exists($filter)) {
                throw new ClassException("Could not find the class {$filter}", 0, 1);
            }

            $reflection = new ReflectionClass($filter);
            $filter = $reflection->newInstanceArgs($args);
        }

        if (is_object($filter instanceof IActionFilter)) {
            $this->filters[] = $filter;
            return $this;
        }

        $this->filters[] = new ActionFilterDelegate($filter);
        return $this;
    }

    public function build(): IRouteHandler
    {
        $action = $this->action;
        $handler = new RequestDelegate(function ($context) use ($action) {
            foreach (array_reverse($this->filters) as $filter) {
                $action = new ActionDelegate(function ($context) use ($filter, $action) {
                    return $filter($context, $action);
                });
            }
            $actionDescriptor = new ActionDescriptor($action, 'invoke');
            $actionContext = new ActionContext($actionDescriptor, $context);
            return $action($actionContext);
        });

        return new RouteHandler($handler);
    }
}
