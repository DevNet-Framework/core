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
use Artister\System\Process\Task;

class MvcRouteHandler implements IRouteHandler
{
    private IServiceProvider $Provider;
    private MvcOptions $Options;
    private $Target;

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
