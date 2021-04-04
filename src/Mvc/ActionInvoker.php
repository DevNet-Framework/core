<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc;

use DevNet\Web\Dispatcher\IRequestHandler;
use DevNet\Web\Mvc\Binder\IValueProvider;
use DevNet\Web\Mvc\ActionContext;
use DevNet\Web\Http\HttpContext;
use DevNet\System\Dependency\Activator;
use DevNet\System\Async\Task;

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
        $controller       = Activator::CreateInstance($this->ControllerName, $httpContext->RequestServices);
        $actionDescriptor = new ActionDescriptor($controller, $this->ActionName);
        $actionContext    = new ActionContext($actionDescriptor, $httpContext, $this->ValueProvider);
        
        $httpContext->addAttribute("ActionContext", $actionContext);

        return $controller($httpContext);
    }
}
