<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IApplicationBuilder;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\System\Async\AsyncFunction;
use DevNet\System\Async\Tasks\Task;
use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Exceptions\PropertyException;
use Closure;

class ApplicationBuilder implements IApplicationBuilder
{
    use \DevNet\System\Extension\ExtenderTrait;

    private IserviceProvider $provider;
    private MiddlewareFactory $middlewareFactoty;
    private array $middlewares;

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
        $this->middlewareFactoty = new MiddlewareFactory($provider);
        $this->middlewares = [];
    }

    /**
     * @param IMiddleware | Closure | string $middleware
     */
    public function use($middleware)
    {
        if (is_object($middleware)) {
            if ($middleware instanceof Closure) {
                $middleware = new RequestDelegate($middleware);
            } else if (!$middleware instanceof IMiddleware) {
                throw new \Exception("invalide type, class must be of type DevNet\Web\Hosting\IMiddleware");
            }
        }

        if (is_string($middleware)) {
            $middleware = $this->middlewareFactoty->create($middleware);
        }

        $this->middlewares[] = $middleware;
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
