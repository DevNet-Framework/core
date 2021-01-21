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
use Artister\System\Process\Task;
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
        if (is_object($this->Target))
        {
            $routeContext->Handler = $this->Target;
        }

        if (is_string($this->Target))
        {
            if (!class_exists($this->Target))
            {
                throw ClassException::classNotFound($this->Target);
            }

            $handler = Activator::CreateInstance($this->Target, $this->ServiceProvider);
            $routeContext->Handler = $handler;
        }

        return Task::completedTask();
    }
}