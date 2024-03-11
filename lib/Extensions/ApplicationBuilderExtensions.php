<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Extensions;

use DevNet\System\Exceptions\ClassException;
use DevNet\Web\Endpoint\EndpointRouteBuilder;
use DevNet\Web\Hosting\IApplicationBuilder;
use DevNet\Web\Http\Middleware\IMiddleware;
use DevNet\Web\Middlewares\AuthenticationMiddleware;
use DevNet\Web\Middlewares\Diagnostics\ExceptionHandlerMiddleware;
use DevNet\Web\Middlewares\EndpointMiddleware;
use DevNet\Web\Middlewares\RouterMiddleware;
use DevNet\Web\Routing\IRouteBuilder;
use ReflectionClass;
use Closure;

class ApplicationBuilderExtensions
{
    public static function useMiddleware(IApplicationBuilder $app, string $middlewareName, ...$args): void
    {
        if (!class_exists($middlewareName)) {
            throw new ClassException("Could not find middleware class {$middlewareName}", 0, 1);
        }

        $interfaces = class_implements($middlewareName);
        if (!in_array(IMiddleware::class, $interfaces)) {
            throw new ClassException("{$middlewareName} must implements IMiddleware inteface", 0, 1);
        }

        $reflection = new ReflectionClass($middlewareName);
        $middleware = $reflection->newInstanceArgs($args);

        $app->use($middleware);
    }

    public static function UseExceptionHandler(IApplicationBuilder $app, ?string $errorHandlingPath = null): void
    {
        $app->use(new ExceptionHandlerMiddleware($errorHandlingPath));
    }

    public static function useRouter(IApplicationBuilder $app): void
    {
        $app->use(new RouterMiddleware());
    }

    public static function useAuthentication(IApplicationBuilder $app): void
    {
        $app->use(new AuthenticationMiddleware());
    }

    public static function useEndpoint(IApplicationBuilder $app, Closure $configure): void
    {
        $routeBuilder = $app->Provider->getService(IRouteBuilder::class);
        $endpointBuilder = new EndpointRouteBuilder($routeBuilder);
        $configure($endpointBuilder);
        $app->use(new EndpointMiddleware());
    }
}
