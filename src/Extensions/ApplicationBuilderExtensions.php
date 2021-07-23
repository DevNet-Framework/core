<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Extensions;

use DevNet\Core\Dispatcher\IApplicationBuilder;
use DevNet\Core\Router\RouteBuilder;
use DevNet\Core\Middlewares\RouterMiddleware;
use DevNet\Core\Middlewares\EndpointMiddleware;
use DevNet\Core\Middlewares\ExceptionMiddleware;
use DevNet\Core\Middlewares\AuthenticationMiddleware;
use DevNet\Core\Middlewares\AuthorizationMiddleware;
use Closure;

class ApplicationBuilderExtensions
{
    public static function UseDeveloperExceptionHandler(IApplicationBuilder $app)
    {
        $app->use(new ExceptionMiddleware());
    }

    public static function UseExceptionHandler(IApplicationBuilder $app, ?string $ErrorHandlingPath = '')
    {
        $app->use(new ExceptionMiddleware($ErrorHandlingPath));
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
