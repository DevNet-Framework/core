<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\System\Async\AsyncFunction;
use DevNet\System\Async\Tasks\Task;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IApplicationBuilder;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;

class ApplicationBuilder implements IApplicationBuilder
{
    use \DevNet\System\Extension\ExtenderTrait;

    private IserviceProvider $provider;
    private array $middlewares = [];

    public function __get(string $name)
    {
        if ($name == 'Provider') {
            return $this->provider;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(IServiceProvider $provider)
    {
        $this->provider = $provider;
    }

    public function use(callable $middleware): void
    {
        if ($middleware instanceof IMiddleware) {
            $this->middlewares[] = $middleware;
            return;
        }

        $this->middlewares[] = new MiddlewareDelegate($middleware);
    }

    public function pipe(callable $middleware, $next): RequestDelegate
    {
        return new RequestDelegate(function (HttpContext $context) use ($middleware, $next) {
            $middlewareAsync = new AsyncFunction($middleware);
            return $middlewareAsync($context, $next);
        });
    }

    public function Build(): RequestDelegate
    {
        $app = new RequestDelegate(function (HttpContext $context): Task {

            $RequestHandler = $context->getAttribute('Handler');
            if ($RequestHandler) {
                throw new \Exception("The request has reached the end of the pipeline without being executed the endpoint");
            }

            $context->Response->setStatusCode(404);
            return Task::completedTask();
        });

        foreach (array_reverse($this->middlewares) as $middleware) {
            $app = $this->pipe($middleware, $app);
        }

        return $app;
    }
}
