<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Router\RouteBuilder;
use DevNet\Web\Router\RouteContext;
use DevNet\Web\Router\RouterException;
use DevNet\System\Async\Tasks\Task;

class RouterMiddleware implements IMiddleware
{
    private RouteBuilder $RouteBuilder;

    public function __construct(RouteBuilder $routeBuilder)
    {
        $this->RouteBuilder = $routeBuilder;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $request    = $context->Request;
        $httpMethod = $request->Method;
        $urlPath    = $request->Uri->Path;

        $trimmedPath = $this->trimDuplicateSlashes($urlPath);

        if ($trimmedPath) {
            return new Task(function () use ($context, $trimmedPath) {
                $context->Response->setStatusCode(302);
                $context->Response->Headers->add('Location', $trimmedPath);
            });
        }

        $router = $this->RouteBuilder->build();
        $routeContext = new RouteContext($httpMethod, $urlPath);
        if ($router->matchRoute($routeContext)) {
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
