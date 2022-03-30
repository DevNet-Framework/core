<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\Web\Controller\ActionContext;
use DevNet\Web\Controller\ControllerException;
use DevNet\Web\Controller\Binder\IValueProvider;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IRequestHandler;
use DevNet\System\Dependency\Activator;
use DevNet\System\Exceptions\ClassException;

class ActionInvoker implements IRequestHandler
{
    private string $controllerName;
    private string $actionName;
    private IValueProvider $valueProvider;

    public function __construct(string $controllerName, string $actionName, IValueProvider $provider)
    {
        $this->controllerName = $controllerName;
        $this->actionName     = $actionName;
        $this->valueProvider  = $provider;
    }

    public function __invoke(HttpContext $httpContext)
    {
        try {
            $controller = Activator::CreateInstance($this->controllerName, $httpContext->RequestServices);
        } catch (ClassException $exception) {
            throw new ControllerException("Not found Controller : {$this->controllerName}", 404);
        }

        if (!method_exists($this->controllerName, $this->actionName)) {
            throw new ControllerException("Undefined method : {$this->controllerName}::{$this->actionName}()", 404);
        }

        $actionDescriptor = new ActionDescriptor($controller, $this->actionName);
        $actionContext    = new ActionContext($actionDescriptor, $httpContext, $this->valueProvider);

        $httpContext->addAttribute("ActionContext", $actionContext);
        return $controller($httpContext);
    }
}
