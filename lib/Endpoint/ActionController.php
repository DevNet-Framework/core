<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\Exceptions\TypeException;
use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use DevNet\Web\Endpoint\Results\ContentResult;
use DevNet\Web\Endpoint\Results\JsonResult;
use DevNet\Web\Endpoint\Results\RedirectResult;
use DevNet\Web\Endpoint\Results\StatusCodeResult;
use DevNet\Web\Endpoint\Results\ViewResult;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Security\Tokens\Csrf\IAntiforgery;
use DevNet\Web\View\ViewManager;

abstract class ActionController
{
    use MethodTrait;
    use PropertyTrait;

    public ActionContext $ActionContext;
    public HttpContext $HttpContext;
    public array $ViewData = [];

    public function view(null|object|array $data = null, ?string $name = null): ViewResult
    {
        if (!$name) {
            $prefix         = $this->HttpContext->RouteContext->RouteData->Values['prefix'];
            $controllerName = $this->ActionContext->ActionDescriptor->ClassName;
            $controllerName = str_replace('Controller', '', $this->ActionContext->ActionDescriptor->ClassName);
            $actionName     = $this->ActionContext->ActionDescriptor->ActionName;

            if (strtolower(strstr($actionName, "_", true)) == "async") {
                $actionName = substr(strstr($actionName, "_"), 1);
            }

            $name = ucwords($prefix . $controllerName, '/') . '/' . $actionName;
        }

        $view = $this->HttpContext->RequestServices->getService(ViewManager::class);
        $view->setData($this->ViewData);
        $antiforgery = $this->HttpContext->RequestServices->getService(IAntiforgery::class);
        if ($antiforgery) {
            $view->inject('Antiforgery', $antiforgery);
        }

        return new ViewResult($view($name, $data), 200);
    }

    public function content(string $content, string $contentType = 'text/plain'): ContentResult
    {
        return new ContentResult($content, $contentType);
    }

    public function json(object|array $data, $statusCode = 200): JsonResult
    {
        return new JsonResult($data, $statusCode);
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
