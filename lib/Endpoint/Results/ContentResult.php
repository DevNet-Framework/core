<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Results;

use DevNet\System\Async\Task;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\IActionResult;

class ContentResult implements IActionResult
{
    private string $content;
    private string $contentType = "text/plain";

    public function __construct(string $content, ?string $contentType = null)
    {
        $this->content = $content;
        if ($contentType) {
            $this->contentType = $contentType;
        }
    }

    public function __invoke(ActionContext $actionContext): Task
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add('Content-Type', $this->contentType);
        $httpContext->Response->Body->write($this->content);
        return Task::completedTask();
    }
}
