<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc;

use DevNet\System\Async\Task;
use DevNet\System\Dependency\Activator;
use DevNet\System\Exceptions\ClassException;
use DevNet\Web\Dispatcher\IRequestHandler;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Mvc\ActionContext;
use DevNet\Web\Mvc\Binder\IValueProvider;
use DevNet\Web\Mvc\ControllerException;

class ActionInvoker implements IRequestHandler
{
    protected string $ControllerName;
    protected string $ActionName;
    protected IValueProvider $ValueProvider;

    public function __construct(string $controllerName, string $actionName, IValueProvider $provider)
    {
        $this->ControllerName = $controllerName;
        $this->ActionName     = $actionName;
        $this->ValueProvider  = $provider;
    }

    public function __invoke(HttpContext $httpContext) : Task
    {
        try
        {
            $controller = Activator::CreateInstance($this->ControllerName, $httpContext->RequestServices);
        }
        catch (ClassException $exception)
        {
            throw new ControllerException("Not found Controller : {$this->ControllerName}", 404);
        }

        if (!method_exists($this->ControllerName, $this->ActionName))
        {
            throw new ControllerException("Undefined method : {$this->ControllerName}::{$this->ActionName}()", 404);
        }

        $actionDescriptor = new ActionDescriptor($controller, $this->ActionName);
        $actionContext    = new ActionContext($actionDescriptor, $httpContext, $this->ValueProvider);
        
        $httpContext->addAttribute("ActionContext", $actionContext);
        return $controller($httpContext);
    }
}
