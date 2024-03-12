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

class StatusCodeResult implements IActionResult
{
    private int $statusCode;

    public function __construct(int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
    }

    public function __invoke(ActionContext $actionContext): Task
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->setStatusCode($this->statusCode);
        return Task::completedTask();
    }
}
