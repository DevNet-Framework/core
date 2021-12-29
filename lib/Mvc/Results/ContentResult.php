<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc\Results;

use DevNet\Core\Mvc\ActionContext;

class ContentResult extends ActionResult
{
    protected string $Content;
    protected string $ContentType;

    public function __construct(string $content, string $contentType)
    {
        $this->Content = $content;
        $this->ContentType = $contentType;
    }

    public function execute(ActionContext $actionContext): void
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add('Content-Type', $this->ContentType);
        $httpContext->Response->Body->write($this->Content);
    }
}
