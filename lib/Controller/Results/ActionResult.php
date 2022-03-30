<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Results;

use DevNet\Web\Controller\IActionResult;
use DevNet\Web\Controller\ActionContext;
use DevNet\System\Async\Tasks\Task;

abstract class ActionResult implements IActionResult
{
    public function executeAsync(ActionContext $controllerContext): Task
    {
        $this->execute($controllerContext);
        return Task::completedTask();
    }

    abstract public function execute(ActionContext $controllerContext): void;
}
