<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Middlewares;

use Artister\DevNet\Router\RouteBuilder;
use Artister\DevNet\Router\RouteContext;
use Artister\DevNet\Dispatcher\IMiddleware;
use Artister\DevNet\Dispatcher\RequestDelegate;
use Artister\DevNet\Http\HttpContext;
use Artister\System\Process\Task;

class RouterMiddleware implements IMiddleware
{
    private RouteBuilder $RouteBuilder;

    public function __construct(RouteBuilder $routeBuilder)
    {
        $this->RouteBuilder = $routeBuilder;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        $request = $context->Request;
        $httpMethod = $request->Method;
        $urlPath = $request->Uri->Path;

        $trimmedPath = $this->trimDuplicateSlashes($urlPath);

        if ($trimmedPath)
        {
            return new Task(function() use ($context, $trimmedPath)
            {
                $context->Response->setStatusCode(302);
                $context->Response->Headers->add('Location', $trimmedPath);
            });
        }

        $router = $this->RouteBuilder->build();
        $routeContext = new RouteContext($httpMethod, $urlPath);
        if ($router->matchRoute($routeContext))
        {
            $context->addAttribute('RouteContext', $routeContext);
            $context->addAttribute('RouteValues', $routeContext->RouteData->Values);
            $handler = $routeContext->Handler;

            if ($handler)
            {
                $context->addAttribute('Handler', $handler);
            }
        }

        return $next($context);
    }

    public function trimDuplicateSlashes(string $urlPath) : ?string
    {
        return preg_match("%//+%", $urlPath, $matches) == 1 ? preg_replace("%//+%", '/', $urlPath) : null;
    }
}