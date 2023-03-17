<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action;

use DevNet\System\Tasks\Task;

interface IActionResult
{
    public function __invoke(ActionContext $actionContext): Task;
}
