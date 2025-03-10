<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Extensions;

use DevNet\System\Exceptions\ClassException;
use DevNet\Core\Endpoint\EndpointRouteBuilder;
use DevNet\Core\Hosting\IApplicationBuilder;
use DevNet\Core\Middlewares\AuthenticationMiddleware;
use DevNet\Core\Middlewares\EndpointMiddleware;
use DevNet\Core\Middlewares\ExceptionHandlerMiddleware;
use DevNet\Core\Middlewares\RouterMiddleware;
use DevNet\Core\Routing\IRouteBuilder;
use DevNet\Http\Middleware\IMiddleware;
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
            throw new ClassException("{$middlewareName} must implements IMiddleware interface", 0, 1);
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
