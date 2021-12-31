<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc;

use DevNet\Core\View\ViewManager;
use DevNet\Core\Mvc\Results\ContentResult;
use DevNet\Core\Mvc\Results\ForbidResult;
use DevNet\Core\Mvc\Results\JsonResult;
use DevNet\Core\Mvc\Results\RedirectResult;
use DevNet\Core\Mvc\Results\ViewResult;
use DevNet\Core\Mvc\Features\HtmlHelper;
use DevNet\Core\Mvc\Features\UrlHelper;
use DevNet\System\Exceptions\ArgumentException;

abstract class Controller extends AbstractController
{
    protected array $ViewData = [];

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
            $controllerName = $this->ActionContext->ActionDescriptor->ControllerName;
            $controllerName = str_replace('Controller', '', $this->ActionContext->ActionDescriptor->ControllerName);
            $actionName     = $this->ActionContext->ActionDescriptor->ActionName;
            $viewName       = ucwords($prefix . $controllerName, '/') . '/' . $actionName;
        }

        $view = $this->ActionContext->HttpContext->RequestServices->getService(ViewManager::class);
        $view->inject('ViewData', $this->ViewData);
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