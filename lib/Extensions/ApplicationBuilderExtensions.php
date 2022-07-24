<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Extensions;

use DevNet\Web\Middleware\IApplicationBuilder;
use DevNet\Web\Exception\ExceptionMiddleware;
use DevNet\Web\Router\RouterMiddleware;
use DevNet\Web\Security\Authentication\AuthenticationMiddleware;
use DevNet\Web\Middleware\EndpointMiddleware;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Router\RouteBuilder;
use DevNet\System\Exceptions\ClassException;
use Closure;

class ApplicationBuilderExtensions
{
    public static function useMiddleware(IApplicationBuilder $app, string $middlewareName, array $args = []): void
    {
        $interfaces = class_implements($middlewareName);
        if ($interfaces === false) {
            throw new ClassException("Could not find middleware {$middlewareName}");
        }

        if (!in_array(IMiddleware::class, $interfaces)) {
            throw new ClassException("{$middlewareName} must implements IMiddleware inteface");
        }

        $app->use(new $middlewareName($args));
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

    public static function useEndpoint(IApplicationBuilder $app, Closure $routeConfig): void
    {
        $routeBuilder = $app->Provider->getService(RouteBuilder::class);
        $routeConfig($routeBuilder);
        $app->use(new EndpointMiddleware());
    }
}
