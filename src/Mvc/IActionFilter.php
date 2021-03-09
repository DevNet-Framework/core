<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\System\Async\Task;

interface IActionFilter
{
    //ActionExecutionContext
    public function onActionExecutionAsync(ActionContext $context, ActionExecutionDelegate $next) : Task;
}