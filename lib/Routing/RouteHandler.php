<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\Async\Task;
use DevNet\Common\Dependency\Activator;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\PropertyTrait;
use DevNet\Web\Endpoint\ActionDelegate;
use DevNet\Web\Endpoint\ActionFilterDelegate;
use DevNet\Web\Endpoint\IActionFilter;
use ReflectionClass;

class RouteHandler implements IRouteHandler
{
    use PropertyTrait;

    private $target;
    private array $filters = [];

    public function __construct($target)
    {
        $this->target = $target;
    }

    public function get_Target()
    {
        return $this->target;
    }

    public function set_Target($value)
    {
        $this->target = $value;
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

    public function handle(RouteContext $routeContext): Task
    {
        if (is_callable($this->target)) {
            $handler = $this->target;
        } else if (is_string($this->target)) {
            $handler = Activator::CreateInstance($this->target, $routeContext->HttpContext->Services);
        } else {
            throw new RouterException("Invalid Argument Type, route endpoint must be of type callable|string");
        }

        $handler = new ActionDelegate(function ($context) use ($handler) {
            $result = $handler($context);
            if ($result instanceof Task) {
                return $result->then(function ($previous) use ($context) {
                    $result = $previous->Result;
                    if (is_object($result) || is_array($result)) {
                        $context->Response->Headers->add("Content-Type", "application/json");
                        $content = json_encode($result);
                        $context->Response->Body->write($content);
                    } else if (is_string($result)) {
                        $context->Response->Headers->add("Content-Type", "text/plain");
                        $context->Response->Body->write($result);
                    }
                });
            } else if (is_object($result) || is_array($result)) {
                $context->Response->Headers->add("Content-Type", "application/json");
                $content = json_encode($result);
                $context->Response->Body->write($content);
            } else if (is_string($result)) {
                $context->Response->Headers->add("Content-Type", "text/plain");
                $context->Response->Body->write($result);
            }

            return Task::completedTask();
        });

        foreach (array_reverse($this->filters) as $filter) {
            $handler = new ActionDelegate(function ($context) use ($filter, $handler) {
                return $filter($context, $handler);
            });
        }

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}
