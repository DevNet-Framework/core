<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc;

interface IActionFilter
{
    //ActionExecutionContext
    public function onActionExecution(ActionContext $context, ActionExecutionDelegate $next);
}