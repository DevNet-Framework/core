<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc;

use Artister\DevNet\View\ViewManager;
use Artister\DevNet\Mvc\Results\ContentResult;
use Artister\DevNet\Mvc\Results\ForbidResult;
use Artister\DevNet\Mvc\Results\JsonResult;
use Artister\DevNet\Mvc\Results\RedirectResult;
use Artister\DevNet\Mvc\Results\ViewResult;

Abstract class Controller extends ControllerBase
{
    protected array $ViewData = [];

    public function view(string $viewName, object $model = null) : ViewResult
    {
        $view = $this->ActionContext->HttpContext->Services->getService(ViewManager::class);
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
