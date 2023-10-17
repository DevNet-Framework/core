<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;

class EndpointMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $routeContext = $context->Items['RouteContext'] ?? null;

        if (!$routeContext) {
            return $next($context);
        }

        $requestHandler = $routeContext->Handler;

        return $requestHandler($context);
    }
}
