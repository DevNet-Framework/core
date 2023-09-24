<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\Async\Task;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;

class RouterMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $urlPath     = $context->Request->Uri->Path;
        $trimmedPath = $this->trimDuplicateSlashes($urlPath);

        if ($trimmedPath) {
            $context->Response->Headers->add('Location', $trimmedPath);
            return Task::completedTask();
        }

        $routeBuilder = $context->RequestServices->getService(RouteBuilder::class);
        $router       = $routeBuilder->build();
        $routeContext = new RouteContext($context);

        if ($router->match($routeContext)) {
            $context->addAttribute('RouteContext', $routeContext);
            $context->addAttribute('RouteValues', $routeContext->RouteData->Values);
            $handler = $routeContext->Handler;

            if ($handler) {
                $context->addAttribute('Handler', $handler);
            }
        } else {
            throw new RouterException("No route maches your request", 404);
        }

        return $next($context);
    }

    public function trimDuplicateSlashes(string $urlPath): ?string
    {
        return preg_match("%//+%", $urlPath, $matches) == 1 ? preg_replace("%//+%", '/', $urlPath) : null;
    }
}
