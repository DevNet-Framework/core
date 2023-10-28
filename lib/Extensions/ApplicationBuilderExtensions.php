<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Extensions;

use DevNet\System\Exceptions\ClassException;
use DevNet\Web\Endpoint\ControllerOptions;
use DevNet\Web\Endpoint\EndpointMiddleware;
use DevNet\Web\Endpoint\EndpointRouteBuilder;
use DevNet\Web\Exception\ExceptionMiddleware;
use DevNet\Web\Middleware\IApplicationBuilder;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Routing\RouterMiddleware;
use DevNet\Web\Routing\IRouteBuilder;
use DevNet\Web\Security\Authentication\AuthenticationMiddleware;
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

    public static function UseExceptionHandler(IApplicationBuilder $app, ?string $errorHandlingPath = ''): void
    {
        $app->use(new ExceptionMiddleware($errorHandlingPath));
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
