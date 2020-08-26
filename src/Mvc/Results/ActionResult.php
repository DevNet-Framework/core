<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc\Results;

use Artister\DevNet\Mvc\IActionResult;
use Artister\DevNet\Mvc\ActionContext;
use Artister\System\Process\Task;

abstract class ActionResult implements IActionResult
{
    protected string $Content;
    protected int $StatusCode;

    public function executeAsync(ActionContext $controllerContext) : Task
    {
        $this->execute($controllerContext);
        return Task::completedTask();
    }

    abstract public function execute(ActionContext $controllerContext) : void;
}