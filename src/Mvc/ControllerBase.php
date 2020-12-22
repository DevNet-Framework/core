<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\Web\Mvc\ActionContext;
use Artister\Web\Mvc\Results\ContentResult;
use Artister\Web\Mvc\Results\ForbidResult;
use Artister\Web\Mvc\Results\JsonResult;
use Artister\Web\Mvc\Results\RedirectResult;
use Artister\Web\Mvc\Results\ViewResult;
use Artister\Web\Mvc\Binder\ParameterBinder;
use Artister\Web\Mvc\Providers\FileValueProvider;
use Artister\Web\Mvc\Providers\FormValueProvider;
use Artister\Web\Mvc\Providers\QueryValueProvider;
use Artister\Web\Mvc\Providers\RouteValueProvider;
use Artister\Web\Dispatcher\IRequestHandler;
use Artister\Web\Http\HttpContext;
use Artister\System\Process\Task;

Abstract class ControllerBase implements IRequestHandler
{
    protected ActionContext $ActionContext;
    protected ActionExecutionDelegate $Action;
    protected HttpContext $HttpContext;
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

    public function __invoke(HttpContext $httpContext) : Task
    {
        $this->HttpContext  = $httpContext;
        $routeContext       = $httpContext->RouteContext;
        $placeholders       = $routeContext->RouteData->Values;
        $actionName         = $placeholders['action'] ?? null;
        $options            = $httpContext->RequestServices->getService(MvcOptions::class);
        $valueProvider      = $options->getValueProviders();

        $valueProvider->add(new RouteValueProvider($routeContext->RouteData->Values));
        $valueProvider->add(new QueryValueProvider());
        $valueProvider->add(new FormValueProvider());
        $valueProvider->add(new FileValueProvider());

        $actionDescriptor       = new ActionDescriptor($this, $actionName);
        $this->ActionContext    = new ActionContext($actionDescriptor, $httpContext, $valueProvider);

        $controllerFilters      = $this->FilterAttributes[self::class] ?? [];
        $actionFilters          = $this->FilterAttributes[strtolower($actionName)] ?? [];
        $filterAttributes       = array_merge($controllerFilters, $actionFilters);

        foreach ($filterAttributes as $filterAttribute)
        {
            $actionFilter           = $filterAttribute[0];
            $options                = $filterAttribute[1];
            $this->ActionFilters[]  = new $actionFilter($options);
        }

        $this->Action = $action = new ActionExecutionDelegate($this, 'next');

        return $action($this->ActionContext);
    }

    public function next(ActionContext $actionContext) : Task
    {
        $actionFilter = array_shift($this->ActionFilters);
        if ($actionFilter)
        {
            return $actionFilter->onActionExecutionAsync($actionContext, $this->Action);
        }

        return $this->execute($actionContext);
    }

    public function execute(ActionContext $actionContext) : Task
    {
        $parameterBinder            = new ParameterBinder();
        $actionReflector            = $actionContext->ActionDescriptor->MethodInfo;
        $arguments                  = $parameterBinder->resolveArguments($actionContext);

        $actioinResult = $actionReflector->invokeArgs($this, $arguments);

        return $actioinResult->executeAsync($this->ActionContext);
    }

    public function filter(string $target, string $filter, array $options = [])
    {
        if ($target)
        {
            strtolower($target);
        }

        $this->FilterAttributes[$target][] = [$filter, $options];
    }

    abstract public function view(string $viewName, object $model = null) : ViewResult;

    abstract public function content(string $content, int $status = 200) : ContentResult;

    abstract public function json(array $data, $statusCode = 200) : JsonResult;

    abstract public function redirect(string $path) : RedirectResult;

    abstract public function forbidResult() : ForbidResult;
}
