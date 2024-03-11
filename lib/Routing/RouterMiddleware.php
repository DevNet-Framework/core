<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\Async\Task;
use DevNet\Web\Http\Message\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;

class RouterMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $urlPath     = $context->Request->Path;
        $trimmedPath = $this->trimDuplicateSlashes($urlPath);

        if ($trimmedPath) {
            $context->Response->Headers->add('Location', $trimmedPath);
            return Task::completedTask();
        }

        $routeBuilder = $context->Services->getService(IRouteBuilder::class);
        $router       = $routeBuilder->build();
        $routeContext = new RouteContext($context);

        if ($router->match($routeContext)) {
            $context->Items->add('RouteContext', $routeContext);
            $context->Items->add('RouteHandler', $routeContext->Handler);
            $context->Request->RouteValues = $routeContext->RouteData->Values;
        } else {
            if (isset($routeContext->RouteData->Routers['forbidden'])) {
                throw new RouterException("The request method '{$context->Request->Method}' not allowed for the matched route!", 405);
            }
            throw new RouterException("No route matches your request!", 404);
        }

        return $next($context);
    }

    public function trimDuplicateSlashes(string $urlPath): ?string
    {
        return preg_match("%//+%", $urlPath, $matches) == 1 ? preg_replace("%//+%", '/', $urlPath) : null;
    }
}
