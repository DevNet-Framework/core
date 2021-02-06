<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\Web\View\ViewManager;
use Artister\Web\Mvc\Results\ContentResult;
use Artister\Web\Mvc\Results\ForbidResult;
use Artister\Web\Mvc\Results\JsonResult;
use Artister\Web\Mvc\Results\RedirectResult;
use Artister\Web\Mvc\Results\ViewResult;

Abstract class Controller extends ControllerBase
{
    protected array $ViewData = [];

    public function view($parameter = null, object $model = null) : ViewResult
    {
        if (!$parameter)
        {
            $viewName = null;
        }
        else if (is_string($parameter))
        {
            $viewName = $parameter;
        }
        else if (is_object($parameter))
        {
            $viewName = null;
            $model = $parameter;
        }
        else
        {
            throw new \Exception("Invalide argument type, parameter 1 must be string or object");
        }
        
        if (!$viewName)
        {
            $controllerName = str_replace("Controller", "",$this->ActionContext->ActionDescriptor->ControllerName);
            $actionName     = $this->ActionContext->ActionDescriptor->ActionName;
            $viewName       = $controllerName."/".$actionName;
        }

        $view = $this->ActionContext->HttpContext->RequestServices->getService(ViewManager::class);
        $view->setData($this->ViewData);
        return new ViewResult($view($viewName, $model), 200);
    }

    public function content(string $content, int $status = 200) : ContentResult
    {
        return new ContentResult($content, $status);
    }

    public function json(array $data, $statusCode = 200) : JsonResult
    {
        return new JsonResult($data, $statusCode);
    }

    public function redirect(string $path) : RedirectResult
    {
        return new RedirectResult($path);
    }

    public function forbidResult() : ForbidResult
    {
        return new ForbidResult();
    }
}
