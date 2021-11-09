<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller\Results;

use DevNet\Core\Controller\IActionResult;
use DevNet\Core\Controller\ActionContext;
use DevNet\System\Async\Task;

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
