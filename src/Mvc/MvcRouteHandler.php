<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\Web\Router\IRouteHandler;
use Artister\Web\Router\RouteContext;
use Artister\System\Dependency\Activator;
use Artister\System\Dependency\IServiceProvider;
use Artister\System\Async\Task;
use Artister\Web\Mvc\Providers\RouteValueProvider;

class MvcRouteHandler implements IRouteHandler
{
    private IServiceProvider $Provider;
    private MvcOptions $Options;
    private array $Target = [];

    public function __construct(IServiceProvider $provider)
    {
        $this->Provider = $provider;
        $this->Options  = $provider->getService(MvcOptions::class);
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }

    public function handle(RouteContext $routeContext) : Task
    {
        $handler = $routeContext->Handler;

        if ($handler)
        {
            return Task::completedTask();
        }

        $prefix         = null;
        $options        = $this->Provider->getService(MvcOptions::class);
        $routeData      = $routeContext->RouteData;
        $controllerName = $this->Target[0] ?? null;
        $actionName     = $this->Target[1] ?? null;
        
        if (!$controllerName)
        {
            $namespace      = $options->getControllerNamespace();
            $controllerName = $routeData->Values['controller'] ?? null;
            $prefix         = ltrim((string)strstr($routeContext->UrlPath, $controllerName, true),'/');
            $controllerName = ucwords($namespace.'\\'.str_replace('/', '\\', $prefix).$controllerName.'Controller', '\\');
        }

        $routeData->Values['prefix'] = $prefix;

        if (!$actionName)
        {
            $actionName = $routeData->Values['action'] ?? null;
        }

        $valueProvider = $options->getValueProviders();
        $valueProvider->add(new RouteValueProvider($routeContext->RouteData->Values));

        $invoker = new ActionInvoker($controllerName, $actionName, $valueProvider);
        $routeContext->Handler = $invoker;

        return Task::completedTask();
    }
}
