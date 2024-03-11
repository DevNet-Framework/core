<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Results;

use DevNet\System\Async\Task;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\IActionResult;

class ViewResult implements IActionResult
{
    private string $content;
    private int $statusCode;

    public function __construct(string $content, int $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function __invoke(ActionContext $actionContext): Task
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add("Content-Type", "text/html");
        $httpContext->Response->Body->write($this->content);
        $httpContext->Response->setStatusCode($this->statusCode);
        return Task::completedTask();
    }
}
