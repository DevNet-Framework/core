<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\System\Async\Task;
use DevNet\System\PropertyTrait;
use DevNet\Web\Action\ActionDescriptor;
use DevNet\Web\Action\ActionInvoker;
use DevNet\Web\Action\Binder\Providers\RouteValueProvider;
use DevNet\Web\Router\IRouteHandler;
use DevNet\Web\Router\RouteContext;

class ControllerRouteHandler implements IRouteHandler
{
    use PropertyTrait;

    private mixed $target = null;

    public function get_Target()
    {
        return $this->target;
    }

    public function set_Target(string|callable|null $value): void
    {
        $this->target = $value;
    }

    public function handle(RouteContext $routeContext): Task
    {
        $handler = $routeContext->Handler;

        if ($handler) {
            return Task::completedTask();
        }

        $prefix         = null;
        $options        = $routeContext->HttpContext->RequestServices->getService(ControllerOptions::class);
        $routeData      = $routeContext->RouteData;
        $controllerName = $this->target[0] ?? null;
        $actionName     = $this->target[1] ?? null;

        if (!$controllerName) {
            $namespace      = $options->getControllerNamespace();
            $controllerName = $routeData->Values['controller'] ?? null;
            $prefix         = ltrim((string)strstr($routeContext->UrlPath, $controllerName, true), '/');
            $controllerName = ucwords($namespace . '\\' . str_replace('/', '\\', $prefix) . $controllerName . 'Controller', '\\');
        }

        $routeData->Values['prefix'] = $prefix;

        if (!$actionName) {
            $actionName = $routeData->Values['action'] ?? null;
        }

        if (!class_exists($controllerName)) {
            throw new ControllerException("Could not find controller {$controllerName}", 404);
        }

        if (!method_exists($controllerName, $actionName)) {
            throw new ControllerException("Call to undefined method {$controllerName}::{$actionName}()", 404);
        }

        $valueProvider = $options->getValueProviders();
        $valueProvider->add(new RouteValueProvider($routeContext->RouteData->Values));

        $actionDescriptor  = new ActionDescriptor($controllerName, $actionName);
        $invoker = new ActionInvoker($actionDescriptor, $valueProvider);
        $routeContext->Handler = $invoker;

        return Task::completedTask();
    }
}
