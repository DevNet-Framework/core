<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Hosting;

use DevNet\System\Async\Task;
use DevNet\Common\Dependency\IServiceProvider;
use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use DevNet\Core\Hosting\WebHostEnvironment;
use DevNet\Http\Message\HttpContext;
use DevNet\Http\Middleware\IMiddleware;
use DevNet\Http\Middleware\MiddlewareDelegate;
use DevNet\Http\Middleware\RequestDelegate;

class ApplicationBuilder implements IApplicationBuilder
{
    use MethodTrait;
    use PropertyTrait;

    private WebHostEnvironment $environment;
    private IServiceProvider $provider;
    private array $middlewares = [];

    public function __construct(WebHostEnvironment $environment, IServiceProvider $provider)
    {
        $this->environment = $environment;
        $this->provider = $provider;
    }

    public function get_Environment(): WebHostEnvironment
    {
        return $this->environment;
    }

    public function get_Provider(): IServiceProvider
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

            $RequestHandler = $context->Items['RouteHandler'] ?? null;
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
