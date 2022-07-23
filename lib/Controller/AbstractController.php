<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\System\Exceptions\ArgumentException;
use DevNet\System\Exceptions\ClassException;
use DevNet\Web\Controller\Features\HtmlHelper;
use DevNet\Web\Controller\Features\UrlHelper;
use DevNet\Web\Controller\Results\ContentResult;
use DevNet\Web\Controller\Results\ForbidResult;
use DevNet\Web\Controller\Results\JsonResult;
use DevNet\Web\Controller\Results\RedirectResult;
use DevNet\Web\Controller\Results\ViewResult;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\View\ViewManager;

abstract class AbstractController
{
    public HttpContext $HttpContext;
    public ActionDescriptor $ActionDescriptor;
    public array $ActionFilters = [];
    protected array $ViewData = [];

    public function filter(string $target, string $filter, array $options = []): void
    {
        $interfaces = class_implements($filter);
        if ($interfaces === false) {
            throw new ClassException("Could not find middleware {$filter}");
        }

        if (!in_array(IMiddleware::class, $interfaces)) {
            throw new ClassException("{$filter} must implements IMiddleware inteface");
        }

        $target = strtolower($target);
        $this->ActionFilters[$target][] = [$filter, $options];
    }

    public function view($parameter = null, object $model = null): ViewResult
    {
        if (!$parameter) {
            $viewName = null;
        } else if (is_string($parameter)) {
            $viewName = $parameter;
        } else if (is_object($parameter)) {
            $viewName = null;
            $model    = $parameter;
        } else {
            throw new \Exception("Invalide argument type, parameter 1 must be string or object");
        }

        if (!$viewName) {
            $prefix         = $this->HttpContext->RouteContext->RouteData->Values['prefix'];
            $controllerName = $this->ActionDescriptor->ControllerName;
            $controllerName = str_replace('Controller', '', $this->ActionDescriptor->ControllerName);
            $actionName     = $this->ActionDescriptor->ActionName;
            $viewName       = ucwords($prefix . $controllerName, '/') . '/' . $actionName;
        }

        $view = $this->HttpContext->RequestServices->getService(ViewManager::class);
        $view->setData($this->ViewData);
        $view->inject('Html', new HtmlHelper($this->HttpContext));
        $view->inject('Url', new UrlHelper($this->HttpContext));
        return new ViewResult($view($viewName, $model), 200);
    }

    public function content(string $content, string $contentType = 'text/plain'): ContentResult
    {
        return new ContentResult($content, $contentType);
    }

    public function json($data, $statusCode = 200): JsonResult
    {
        if (!is_array($data) && !is_object($data)) {
            $class = get_class($this);
            throw new ArgumentException("Argument 1 passed to {$class}::json() must be of the type object | array.");
        }

        return new JsonResult($data, $statusCode);
    }

    public function redirect(string $path): RedirectResult
    {
        return new RedirectResult($path);
    }

    public function forbidResult(): ForbidResult
    {
        return new ForbidResult();
    }
}
