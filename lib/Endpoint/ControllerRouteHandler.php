<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint;

use DevNet\System\Async\Task;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\MethodException;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\PropertyTrait;
use DevNet\Core\Endpoint\Binder\Providers\RouteValueProvider;
use DevNet\Core\Endpoint\Binder\Providers\ValueProvider;
use DevNet\Core\Routing\IRouteHandler;
use DevNet\Core\Routing\RouteContext;

class ControllerRouteHandler implements IRouteHandler
{
    use PropertyTrait;

    private array $target;
    private ControllerOptions $options;

    public function __construct(array $target, ControllerOptions $options)
    {
        if (is_array($target)) {
            if (!is_string($target[0]) || !is_string($target[1])) {
                throw new TypeException(static::class. "::__construct(): Argument #1 must be of type array<string>", 0, 1);
            }
        }

        $this->target = $target;
        $this->options = $options;
    }

    public function handle(RouteContext $routeContext): Task
    {
        $controllerName = $this->target[0];
        $actionName = $this->target[1];
        if (!class_exists($controllerName)) {
            throw new ClassException("Could not find the class {$controllerName}");
        }

        if (!method_exists($controllerName, $actionName)) {
            if (!method_exists($controllerName, "async_" . $actionName)) {
                throw new MethodException("Call to undefined method {$controllerName}::{$actionName}()");
            } else {
                $actionName = "async_" . $actionName;
            }
        }

        $valueProviders = $this->options->getValueProviders();
        $valueProviders->add(new RouteValueProvider($routeContext->RouteData->Values));
        $valueProviders->add(new ValueProvider(['ViewLocation' => $this->options->ViewLocation]));

        $actionDescriptor = new ActionDescriptor($controllerName, $actionName);
        $invoker = new ActionInvoker($actionDescriptor, $valueProviders);
        $routeContext->Handler = $invoker;

        return Task::completedTask();
    }
}
