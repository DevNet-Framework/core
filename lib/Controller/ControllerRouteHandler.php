<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\Web\Router\IRouteHandler;
use DevNet\Web\Router\RouteContext;
use DevNet\Web\Controller\Providers\RouteValueProvider;
use DevNet\System\Async\Tasks\Task;
use DevNet\System\Exceptions\PropertyException;

class ControllerRouteHandler implements IRouteHandler
{
    private array $target = [];

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
        $actionContext = new ActionContext($actionDescriptor, $routeContext->HttpContext, $valueProvider);
        $invoker = new ActionInvoker($actionContext);
        $routeContext->Handler = $invoker;

        return Task::completedTask();
    }
}
