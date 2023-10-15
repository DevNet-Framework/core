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

class JsonResult implements IActionResult
{
    private string $content;

    public function __construct(object|array $data)
    {
        $this->content = json_encode($data);
    }

    public function __invoke(ActionContext $actionContext): Task
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add("Content-Type", "application/json");
        $httpContext->Response->Body->write($this->content);
        return Task::completedTask();
    }
}
