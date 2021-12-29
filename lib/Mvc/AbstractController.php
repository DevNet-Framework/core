<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Mvc\ActionContext;
use DevNet\Core\Mvc\Binder\ParameterBinder;
use DevNet\Core\Mvc\Results\ContentResult;
use DevNet\Core\Mvc\Results\ForbidResult;
use DevNet\Core\Mvc\Results\JsonResult;
use DevNet\Core\Mvc\Results\RedirectResult;
use DevNet\Core\Mvc\Results\ViewResult;
use DevNet\Core\Middleware\IRequestHandler;
use DevNet\System\Async\Tasks\Task;
use DevNet\System\Action;

abstract class AbstractController implements IRequestHandler
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

    public function __invoke(HttpContext $httpContext)
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
        $arguments = $parameterBinder->resolveArguments($actionContext);

        return Task::run(function () use ($actionContext, $arguments)
        {
            $action = new Action([$this, $actionContext->ActionDescriptor->ActionName]);
            $actionResult = yield $action->invokeArgs($arguments);
            $result = yield $actionResult->executeAsync($this->ActionContext);
            return $result;
        });
    }

    public function filter(string $target, string $filter, array $options = [])
    {
        if ($target) {
            strtolower($target);
        }

        $this->FilterAttributes[$target][] = [$filter, $options];
    }

    abstract public function view($parameter, object $model = null): ViewResult;

    abstract public function content(string $content, string $contentType): ContentResult;

    abstract public function json(array $data): JsonResult;

    abstract public function redirect(string $path): RedirectResult;

    abstract public function forbidResult(): ForbidResult;
}
