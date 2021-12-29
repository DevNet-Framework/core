<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc\Results;

use DevNet\Core\Mvc\IActionResult;
use DevNet\Core\Mvc\ActionContext;
use DevNet\System\Async\Tasks\Task;

abstract class ActionResult implements IActionResult
{
    protected string $Content;
    protected int $StatusCode;

    public function executeAsync(ActionContext $controllerContext): Task
    {
        $this->execute($controllerContext);
        return Task::completedTask();
    }

    abstract public function execute(ActionContext $controllerContext): void;
}
