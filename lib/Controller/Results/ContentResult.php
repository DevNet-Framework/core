<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Results;

use DevNet\Web\Controller\ActionContext;

class ContentResult extends ActionResult
{
    private string $content;
    private string $contentType;

    public function __construct(string $content, string $contentType)
    {
        $this->content = $content;
        $this->contentType = $contentType;
    }

    public function execute(ActionContext $actionContext): void
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add('Content-Type', $this->contentType);
        $httpContext->Response->Body->write($this->content);
    }
}
