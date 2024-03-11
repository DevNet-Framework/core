<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\Async\AsyncFunction;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\Binder\IValueProvider;
use DevNet\Web\Endpoint\Binder\ParameterBinder;
use DevNet\Web\Http\Message\HttpContext;
use DevNet\Web\Http\Middleware\IRequestHandler;

class ActionInvoker implements IRequestHandler
{
    private ActionDescriptor $actionDescriptor;
    private ActionDelegate $action;
    private ParameterBinder $binder;
    private array $filters = [];

    public function __construct(ActionDescriptor $actionDescriptor, IValueProvider $provider)
    {
        $this->actionDescriptor = $actionDescriptor;
        $this->filters = $actionDescriptor->FilterAttributes;
        $this->binder = new ParameterBinder($provider);
    }

    public function createInstance(ActionContext $actionContext): object
    {
        $classInfo   = $actionContext->ActionDescriptor->ClassInfo;
        $constructor = $classInfo->getConstructor();
        $instance    = $classInfo->newInstanceWithoutConstructor();

        if (property_exists($instance, 'ActionContext')) {
            $instance->ActionContext = $actionContext;
        }

        if (property_exists($instance, 'HttpContext')) {
            $instance->HttpContext = $actionContext->HttpContext;
        }

        if (!$constructor) {
            return $instance;
        }

        $services   = $actionContext->HttpContext->Services;
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

        $constructor->invokeArgs($instance, $arguments);

        return $instance;
    }

    public function getNextFilter(): ?IActionFilter
    {
        $attribute = array_shift($this->filters);
        if ($attribute) {
            return $attribute->newInstance();
        }

        return null;
    }

    public function __invoke(HttpContext $httpContext)
    {
        $actionContext = new ActionContext($this->actionDescriptor, $httpContext);
        $instance = $this->createInstance($actionContext);
        $arguments = $this->binder->resolveArguments($actionContext);

        $this->action = new ActionDelegate(function (ActionContext $context) use ($instance, $arguments) {
            $actionFilter = $this->getNextFilter();
            if ($actionFilter) {
                return $actionFilter($context, $this->action);
            }

            if ((strtolower(strstr($context->ActionDescriptor->ActionName, "_", true)) == "async")) {
                $action = new AsyncFunction([$instance, $context->ActionDescriptor->ActionName]);
                $asyncResult = $action->invoke($arguments);
                return $asyncResult->then(function ($previous) use ($context) {
                    $actionResult = $previous->Result;
                    $task = $actionResult($context);
                    while (!$task->IsCompleted) {
                        yield;
                    }

                    return $task->Result;
                });
            } else {
                $action = $context->ActionDescriptor->ActionName;
                $actionResult = $instance->$action(...$arguments);
                return $actionResult($context);
            }
        });

        return $this->action->invoke($actionContext);
    }
}
