<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\System\Async\Task;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\ObjectTrait;
use DevNet\Web\Http\HttpContext;
use Generator;

class ApplicationBuilder implements IApplicationBuilder
{
    use ObjectTrait;

    private IserviceProvider $provider;
    private array $middlewares = [];

    public function __construct(IServiceProvider $provider)
    {
        $this->provider = $provider;
    }

    public function get_Provider(): IserviceProvider
    {
        return $this->provider;
    }

    public function use(callable $middleware): void
    {
        if ($middleware instanceof IMiddleware) {
            $this->middlewares[] = $middleware;
            return;
        }

        $this->middlewares[] = new MiddlewareDelegate($middleware);
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
            $app = new RequestDelegate(function (HttpContext $context) use ($middleware, $app) {
                return $middleware($context, $app);
            });
        }

        return $app;
    }
}
