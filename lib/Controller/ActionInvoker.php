<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\System\Action;
use DevNet\System\Tasks\AsyncFunction;
use DevNet\System\Tasks\Task;
use DevNet\Web\Controller\ActionContext;
use DevNet\Web\Controller\Binder\ParameterBinder;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\IRequestHandler;
use DevNet\Web\Middleware\RequestDelegate;

class ActionInvoker implements IRequestHandler
{
    private ActionContext $actionContext;
    private RequestDelegate $action;
    private array $filters = [];

    public function __construct(ActionContext $actionContext)
    {
        $this->actionContext = $actionContext;
        $this->filters = $actionContext->ActionDescriptor->FilterAttributes;
    }

    public function createController(): object
    {
        $classInfo   = $this->actionContext->ActionDescriptor->ClassInfo;
        $constructor = $classInfo->getConstructor();
        $controller  = $classInfo->newInstanceWithoutConstructor();

        $controller->HttpContext = $this->actionContext->HttpContext;
        $controller->ActionDescriptor = $this->actionContext->ActionDescriptor;

        if (!$constructor) {
            return $controller;
        }

        $services   = $this->actionContext->HttpContext->RequestServices;
        $parameters = $constructor->getParameters();
        $arguments  = [];

        foreach ($parameters as $parameter) {
            $parameterType = '';
            if ($parameter->getType()) {
                $parameterType = $parameter->getType()->getName();
            }

            if (!$services->contains($parameterType)) {
                break;
            }

            $arguments[] = $services->getService($parameterType);
        }

        $constructor->invokeArgs($controller, $arguments);

        return $controller;
    }

    public function getNextFilter(): ?IMiddleware
    {
        $attribute = array_shift($this->filters);
        if ($attribute) {
            return $attribute->newInstance();
        }

        return null;
    }

    public function __invoke(HttpContext $httpContext)
    {
        $controller = $this->createController();
        $this->action = new RequestDelegate(function (HttpContext $httpContext) use ($controller) {
            $actionFilter = $this->getNextFilter();
            if ($actionFilter) {
                $asyncFilter = new AsyncFunction($actionFilter);
                return $asyncFilter($httpContext, $this->action);
            }

            $parameterBinder = new ParameterBinder();
            $arguments = $parameterBinder->resolveArguments($this->actionContext);
            $action = new Action([$controller, $this->actionContext->ActionDescriptor->ActionName]);

            return Task::run(function () use ($action, $arguments) {
                $actionResult = yield $action->invokeArgs($arguments);
                $result = yield $actionResult->executeAsync($this->actionContext);
                return $result;
            });
        });

        return $this->action->invoke($httpContext);
    }
}
