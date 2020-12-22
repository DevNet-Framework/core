<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc\Filters;

use Artister\Web\Http\HttpContext;
use Artister\Web\Mvc\IActionFilter;
use Artister\Web\Mvc\ActionExecutionDelegate;
use Artister\Web\Mvc\ActionContext;
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