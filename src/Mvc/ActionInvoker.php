<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\Web\Dispatcher\IRequestHandler;
use Artister\Web\Mvc\Binder\IValueProvider;
use Artister\Web\Mvc\ActionContext;
use Artister\Web\Http\HttpContext;
use Artister\System\Dependency\Activator;
use Artister\System\Async\Task;

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
