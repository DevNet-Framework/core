<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint;

use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use DevNet\Core\Endpoint\Results\ContentResult;
use DevNet\Core\Endpoint\Results\FileResult;
use DevNet\Core\Endpoint\Results\JsonResult;
use DevNet\Core\Endpoint\Results\RedirectResult;
use DevNet\Core\Endpoint\Results\StatusCodeResult;
use DevNet\Core\Endpoint\Results\ViewResult;
use DevNet\Http\Message\HttpContext;
use DevNet\Security\Tokens\Csrf\IAntiForgery;
use DevNet\Core\View\ViewManager;

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

        $viewLocation = $this->ActionContext->HttpContext->Items->getValue('ViewLocation');
        if (!$viewLocation) {
            $viewLocation = '/Views';
        }

        $directory = dirname($this->ActionContext->ActionDescriptor->ClassInfo->getFileName(), 2) . $viewLocation;
        $view = new ViewManager($directory, $this->HttpContext->Services);
        $view->inject('User', $this->ActionContext->HttpContext->User);

        $antiforgery = $this->ActionContext->HttpContext->Services->getService(IAntiForgery::class);
        if ($antiforgery) {
            $view->inject('AntiForgery', $antiforgery);
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
