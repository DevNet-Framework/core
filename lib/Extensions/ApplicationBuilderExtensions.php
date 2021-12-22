<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Extensions;

use DevNet\Core\Middleware\IApplicationBuilder;
use DevNet\Core\Exception\ExceptionMiddleware;
use DevNet\Core\Router\RouterMiddleware;
use DevNet\Core\Security\Authentication\AuthenticationMiddleware;
use DevNet\Core\Security\Authorization\AuthorizationMiddleware;
use DevNet\Core\Middleware\EndpointMiddleware;
use DevNet\Core\Router\RouteBuilder;
use Closure;

class ApplicationBuilderExtensions
{
    public static function UseExceptionHandler(IApplicationBuilder $app, ?string $errorHandlingPath = '')
    {
        $app->use(new ExceptionMiddleware($errorHandlingPath));
    }

    public static function useRouter(IApplicationBuilder $app)
    {
        $routeBuilder = $app->Provider->getService(RouteBuilder::class);
        $app->use(new RouterMiddleware($routeBuilder));
    }

    public static function useAuthentication(IApplicationBuilder $app)
    {
        $app->use(new AuthenticationMiddleware());
    }

    public static function useAuthorization(IApplicationBuilder $app)
    {
        $app->use(new AuthorizationMiddleware());
    }

    public static function useEndpoint(IApplicationBuilder $app, Closure $routeConfig)
    {
        $routeBuilder = $app->Provider->getService(RouteBuilder::class);
        $routeConfig($routeBuilder);
        $app->use(new EndpointMiddleware());
    }
}
