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
use DevNet\System\Exceptions\PropertyException;
use DevNet\System\Async\Tasks\Task;
use DevNet\System\Action;

abstract class AbstractController implements IRequestHandler
{
    protected HttpContext $HttpContext;
    protected ActionContext $ActionContext;
    private ActionExecutionDelegate $action;
    private array $filterAttributes = [];
    private array $actionFilters = [];

    public function __invoke(HttpContext $httpContext)
    {
        $this->HttpContext   = $httpContext;
        $this->ActionContext = $httpContext->ActionContext;

        $controllerFilters   = $this->filterAttributes[$this->ActionContext->ActionDescriptor->MethodInfo->getDeclaringClass()->getName()] ?? [];
        $actionFilters       = $this->filterAttributes[strtolower($this->ActionContext->ActionDescriptor->ActionName)] ?? [];
        $filterAttributes    = array_merge($controllerFilters, $actionFilters);

        foreach ($filterAttributes as $filterAttribute) {
            $actionFilter           = $filterAttribute[0];
            $options                = $filterAttribute[1];
            $this->actionFilters[]  = new $actionFilter($options);
        }

        $this->action = $action = new ActionExecutionDelegate($this, 'next');

        return $action($this->ActionContext);
    }

    public function next(ActionContext $actionContext)
    {
        $actionFilter = array_shift($this->actionFilters);
        if ($actionFilter) {
            return $actionFilter->onActionExecution($actionContext, $this->action);
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

        $this->filterAttributes[$target][] = [$filter, $options];
    }

    abstract public function view($parameter, object $model = null): ViewResult;

    abstract public function content(string $content, string $contentType): ContentResult;

    abstract public function json(array $data): JsonResult;

    abstract public function redirect(string $path): RedirectResult;

    abstract public function forbidResult(): ForbidResult;
}
