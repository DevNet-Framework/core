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