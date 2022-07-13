<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\Web\Controller\Results\ContentResult;
use DevNet\Web\Controller\Results\ForbidResult;
use DevNet\Web\Controller\Results\JsonResult;
use DevNet\Web\Controller\Results\RedirectResult;
use DevNet\Web\Controller\Results\ViewResult;
use DevNet\Web\Http\HttpContext;

abstract class AbstractController
{
    public HttpContext $HttpContext;
    public ActionDescriptor $ActionDescriptor;
    public array $ActionFilters = [];

    public function filter(string $target, string $filter, array $options = []): void
    {
        $target = strtolower($target);
        $this->ActionFilters[$target][] = [$filter, $options];
    }

    abstract public function view($parameter, object $model = null): ViewResult;

    abstract public function content(string $content, string $contentType): ContentResult;

    abstract public function json(array $data): JsonResult;

    abstract public function redirect(string $path): RedirectResult;

    abstract public function forbidResult(): ForbidResult;
}
