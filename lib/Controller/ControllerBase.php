<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Controller\ActionContext;
use DevNet\Web\Controller\Binder\ParameterBinder;
use DevNet\Web\Controller\Results\ContentResult;
use DevNet\Web\Controller\Results\ForbidResult;
use DevNet\Web\Controller\Results\JsonResult;
use DevNet\Web\Controller\Results\RedirectResult;
use DevNet\Web\Controller\Results\ViewResult;
use DevNet\Web\Middleware\IRequestHandler;
use DevNet\System\Async\Task;

abstract class ControllerBase implements IRequestHandler
{
    protected HttpContext $HttpContext;
    protected ActionContext $ActionContext;
    protected ActionExecutionDelegate $Action;
    protected array $FilterAttributes = [];
    protected array $ActionFilters = [];

    /**
     * Read-only for all properties.
     * @return mixed same as the type of requested property.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __invoke(HttpContext $httpContext): Task
    {
        $this->HttpContext   = $httpContext;
        $this->ActionContext = $httpContext->ActionContext;

        $controllerFilters   = $this->FilterAttributes[$this->ActionContext->ActionDescriptor->MethodInfo->getDeclaringClass()->getName()] ?? [];
        $actionFilters       = $this->FilterAttributes[strtolower($this->ActionContext->ActionDescriptor->ActionName)] ?? [];
        $filterAttributes    = array_merge($controllerFilters, $actionFilters);

        foreach ($filterAttributes as $filterAttribute) {
            $actionFilter           = $filterAttribute[0];
            $options                = $filterAttribute[1];
            $this->ActionFilters[]  = new $actionFilter($options);
        }

        $this->Action = $action = new ActionExecutionDelegate($this, 'next');

        return $action($this->ActionContext);
    }

    public function next(ActionContext $actionContext): Task
    {
        $actionFilter = array_shift($this->ActionFilters);
        if ($actionFilter) {
            return $actionFilter->onActionExecution($actionContext, $this->Action);
        }

        return $this->execute($actionContext);
    }

    public function execute(ActionContext $actionContext): Task
    {
        $parameterBinder = new ParameterBinder();
        $actionReflector = $actionContext->ActionDescriptor->MethodInfo;
        $arguments       = $parameterBinder->resolveArguments($actionContext);
        $actioinResult   = $actionReflector->invokeArgs($this, $arguments);

        return $actioinResult->executeAsync($this->ActionContext);
    }

    public function filter(string $target, string $filter, array $options = [])
    {
        if ($target) {
            strtolower($target);
        }

        $this->FilterAttributes[$target][] = [$filter, $options];
    }

    abstract public function view($parameter, object $model = null): ViewResult;

    abstract public function content(string $content, int $status = 200): ContentResult;

    abstract public function json(array $data, $statusCode = 200): JsonResult;

    abstract public function redirect(string $path): RedirectResult;

    abstract public function forbidResult(): ForbidResult;
}
