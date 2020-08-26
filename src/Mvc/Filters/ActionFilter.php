<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc\Filters;

use Artister\DevNet\Http\HttpContext;
use Artister\DevNet\Mvc\IActionFilter;
use Artister\DevNet\Mvc\ActionExecutionDelegate;
use Artister\DevNet\Mvc\ActionContext;
use Artister\System\Process\Task;

class ActionFilter implements IActionFilter
{
    public function onActionExecutionAsync(ActionContext $context, ActionExecutionDelegate $next) : Task
    {
        $httContext = $context->ServiceProvider->getService(HttpContext::class);
        $httContext->Response->Body->write(" befor execution ");
        $task = $next($context);
        $httContext->Response->Body->write(" after execution ");

        return $task;
    }
}