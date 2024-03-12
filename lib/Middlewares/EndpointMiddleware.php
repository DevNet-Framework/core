<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Middlewares;

use DevNet\Http\Message\HttpContext;
use DevNet\Http\Middleware\IMiddleware;
use DevNet\Http\Middleware\RequestDelegate;

class EndpointMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $requestHandler = $context->Items['RouteHandler'] ?? null;

        if (!$requestHandler) {
            return $next($context);
        }

        return $requestHandler($context);
    }
}
