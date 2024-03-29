<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

use DevNet\System\Exceptions\TypeException;
use DevNet\System\MethodTrait;
use DevNet\Http\Middleware\IRequestHandler;
use DevNet\Http\Middleware\RequestDelegate;

class RouteBuilder implements IRouteBuilder
{
    use MethodTrait;

    private array $routes = [];

    /**
     * Adds a route that only matches HTTP requests for the given pattern and verb.
     */
    public function map(string $pattern, IRouteHandler $handler, ?string $verb = null): IRouteHandler
    {
        $this->routes[] = new Route($handler, $pattern, $verb);
        return $handler;
    }

    /**
     * Map the route with any specified Http verb.
     */
    public function mapRoute(string $pattern, callable $handler, ?string $verb = null): IRouteHandler
    {
        if (!$handler instanceof IRequestHandler and !$handler instanceof RequestDelegate) {
            $handler = new RequestDelegate($handler);
        }

        $routeHandler = new RouteHandler($handler);
        return $this->map($pattern, $routeHandler, $verb);
    }

    /**
     * Map the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'GET');
    }

    /**
     * Map the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'POST');
    }

    /**
     * Map the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'DELETE');
    }

    /**
     * Map the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'PUT');
    }

    /**
     * Map the route using the Http Verb PATCH.
     */
    public function mapPatch(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'PATCH');
    }

    /**
     * from the specified routes.
     */
    public function build(): IRouter
    {
        $routeCollection = new RouteCollection();
        foreach ($this->routes as $route) {
            $routeCollection->add($route);
        }
        return $routeCollection;
    }
}
