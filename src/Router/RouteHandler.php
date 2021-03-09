<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Router;

use Artister\System\Dependency\Activator;
use Artister\System\Dependency\IServiceProvider;
use Artister\System\Async\Task;
use Artister\System\Exceptions\ClassException;

class RouteHandler implements IRouteHandler
{
    private IServiceProvider $ServiceProvider;
    private $Target;

    public function __construct(IServiceProvider $serviceProvider, $target) {

        $this->ServiceProvider = $serviceProvider;
        $this->Target = $target;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
    
    public function handle(RouteContext $routeContext) : Task
    {
        $target  = null;

        if (is_string($this->Target))
        {
            $target = $this->Target;
        }
        else if (is_object($this->Target))
        {
            $handler = $this->Target;
        }
        else
        {
            throw new \Exception("Invalid Argument Type, route endpoint must be of type callable|string");
        }

        if ($target)
        {
            if (!class_exists($target))
            {
                throw ClassException::classNotFound($target);
            }

            $handler = Activator::CreateInstance($target, $this->ServiceProvider);
        }

        $routeContext->Handler = $handler;

        return Task::completedTask();
    }
}