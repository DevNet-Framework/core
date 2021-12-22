<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Middleware;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Middleware\IMiddleware;
use DevNet\Core\Middleware\RequestDelegate;

class EndpointMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $requestHandler = $context->Handler;

        if (!$requestHandler) {
            return $next($context);
        }

        return $requestHandler($context);
    }
}
