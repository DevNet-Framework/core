<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Endpoint\Results;

use DevNet\System\Async\Task;
use DevNet\Core\Endpoint\ActionContext;
use DevNet\Core\Endpoint\IActionResult;

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
        $scheme      = $httpContext->Request->Scheme;
        $host        = $httpContext->Request->Host;

        if (strpos($this->path, "/") !== 0) {
            $this->path = "/{$this->path}";
        }

        $url = $scheme . '://' . $host . $this->path;
        $httpContext->Response->Headers->add("Location", $url);
        return Task::completedTask();
    }
}
