<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc;

use Artister\DevNet\Router\IRouteHandler;
use Artister\DevNet\Router\RouteContext;
use Artister\DevNet\Dependency\Activator;
use Artister\DevNet\Dependency\IServiceProvider;
use Artister\System\Process\Task;

class MvcRouteHandler implements IRouteHandler
{
    private IServiceProvider $Provider;
    private MvcOptions $Options;

    public function __construct(IServiceProvider $provider)
    {
        $this->Provider = $provider;
        $this->Options  = $provider->getService(MvcOptions::class);
    }

    public function setTarget($target) : void
    {
        $this->Target = $target;
    }

    public function handle(RouteContext $routeContext) : Task
    {
        $handler = $routeContext->Handler;

        if ($handler)
        {
            return Task::completedTask();
        }

        $placeholders   = $routeContext->RouteData->Values;
        $controllerName = $placeholders['controller'] ?? null;

        if (!$controllerName)
        {
            return Task::completedTask();
        }

        $controllerName         = ucfirst($placeholders['controller']).'Controller';
        $controllerNamespace    = $this->Options->getControllerNamespace();
        $controllerClass        = $controllerNamespace .'\\'.$controllerName;

        if (is_string($controllerClass))
        {
            $handler = Activator::CreateInstance($controllerClass, $this->Provider);
            $routeContext->Handler = $handler;
        }

        return Task::completedTask();
    }
}
