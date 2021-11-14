<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\Web\Http\HttpContext;
use DevNet\System\Async\Task;

interface IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next): Task;
}