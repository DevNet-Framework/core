<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use DevNet\Web\Endpoint\Results\ContentResult;
use DevNet\Web\Endpoint\Results\FileResult;
use DevNet\Web\Endpoint\Results\JsonResult;
use DevNet\Web\Endpoint\Results\RedirectResult;
use DevNet\Web\Endpoint\Results\StatusCodeResult;
use DevNet\Web\Endpoint\Results\ViewResult;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;
use DevNet\Web\View\ViewManager;

abstract class Controller
{
    use MethodTrait;
    use PropertyTrait;

    public ActionContext $ActionContext;
    public HttpContext $HttpContext;
    public array $ViewData = [];

    public function view(array $data = [], ?string $name = null): ViewResult
    {
        if (!$name) {
            $controllerName = $this->ActionContext->ActionDescriptor->ClassName;
            $controllerName = str_replace('Controller', '', $this->ActionContext->ActionDescriptor->ClassName);
            $actionName     = $this->ActionContext->ActionDescriptor->ActionName;

            if (strtolower(strstr($actionName, "_", true)) == "async") {
                $actionName = substr(strstr($actionName, "_"), 1);
            }

            $name = $controllerName . '/' . $actionName;
        }

        $viewLocation = $this->ActionContext->ValueProvider->getValue('ViewLocation');
        if (!$viewLocation) {
            $viewLocation = '/Views';
        }

        $directory = dirname($this->ActionContext->ActionDescriptor->ClassInfo->getFileName(), 2) . $viewLocation;
        $view = new ViewManager($directory, $this->HttpContext->Services);
        $antiforgery = $this->HttpContext->Services->getService(IAntiforgery::class);
        if ($antiforgery) {
            $view->inject('Antiforgery', $antiforgery);
        }

        return new ViewResult($view($name, $data), 200);
    }

    public function json(object|array $data): JsonResult
    {
        return new JsonResult($data);
    }

    public function content(string $content, ?string $contentType = null): ContentResult
    {
        return new ContentResult($content, $contentType);
    }

    public function file(string $path, ?string $contentType = null): FileResult
    {
        return new FileResult($path, $contentType);
    }

    public function redirect(string $path): RedirectResult
    {
        return new RedirectResult($path);
    }

    public function statusCode(int $code): StatusCodeResult
    {
        return new StatusCodeResult($code);
    }

    public function unauthorized(): StatusCodeResult
    {
        return $this->statusCode(401);
    }

    public function forbid(): StatusCodeResult
    {
        return $this->statusCode(403);
    }

    public function notFound(): StatusCodeResult
    {
        return $this->statusCode(404);
    }

    public function ok(): StatusCodeResult
    {
        return $this->statusCode(200);
    }
}
