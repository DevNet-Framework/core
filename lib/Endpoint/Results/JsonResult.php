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
