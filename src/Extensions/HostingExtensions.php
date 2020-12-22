<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Extensions;

use Artister\Web\Router\RouteBuilder;
use Artister\Web\Dispatcher\ApplicationBuilder;
use Artister\Web\Middlewares\RouterMiddleware;
use Artister\Web\Middlewares\EndpointMiddleware;
use Artister\Web\Middlewares\ExceptionMiddleware;
use Artister\Web\Middlewares\AuthenticationMiddleware;
use Artister\Web\Middlewares\AuthorizationMiddleware;
use Closure;

class HostingExtensions
{
    public static function UseDeveloperExceptionHandler(ApplicationBuilder $app)
    {
        $app->use(new ExceptionMiddleware());
    }

    public static function UseExceptionHandler(ApplicationBuilder $app, ?string $ErrorHandlingPath = '')
    {
        $app->use(new ExceptionMiddleware($ErrorHandlingPath));
    }

    public static function useRouter(ApplicationBuilder $app)
    {
        $routeBuilder = $app->Provider->getService(RouteBuilder::class);
        $app->use(new RouterMiddleware($routeBuilder));
    }

    public static function useAuthentication(ApplicationBuilder $app)
    {
        $app->use(new AuthenticationMiddleware());
    }

    public static function useAuthorization(ApplicationBuilder $app)
    {
        $app->use(new AuthorizationMiddleware());
    }

    public static function useEndpoint(ApplicationBuilder $app, Closure $routeConfig)
    {
        $routeBuilder = $app->Provider->getService(RouteBuilder::class);
        $routeConfig($routeBuilder);
        $app->use(new EndpointMiddleware());
    }
}