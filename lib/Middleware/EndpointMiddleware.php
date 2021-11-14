<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\System\Async\Task;

class EndpointMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next): Task
    {
        $requestHandler = $context->Handler;

        if (!$requestHandler) {
            return $next($context);
        }

        return $requestHandler($context);
    }
}
