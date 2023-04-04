<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action\Results;

use DevNet\System\Async\Task;
use DevNet\Web\Action\ActionContext;
use DevNet\Web\Action\IActionResult;

class RedirectResult implements IActionResult
{
    private string $path;

    public function __construct(string $path, int $statusCode = 302)
    {
        $this->path = $path;
    }

    public function __invoke(ActionContext $actionContext): Task
    {
        $httpContext = $actionContext->HttpContext;
        $scheme      = $httpContext->Request->Uri->Scheme;
        $host        = $httpContext->Request->Uri->Host;
        $port        = $httpContext->Request->Uri->Port;
        $port        = $port != 80 && $port != '' ? ":" . $port : '';

        if (strpos($this->path, "/") !== 0) {
            $this->path = "/{$this->path}";
        }

        $url = $scheme . '://' . $host . $port . $this->path;
        $httpContext->Response->Headers->add("Location", $url);
        return Task::completedTask();
    }
}
