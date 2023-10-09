<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

use DevNet\System\MethodTrait;

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
    public function mapRoute(string $pattern, string|callable|array $handler, ?string $verb = null): IRouteHandler
    {
        $routeHandler = new RouteHandler($handler);
        return $this->map($pattern, $routeHandler, $verb);
    }

    /**
     * Map the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'GET');
    }

    /**
     * Map the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'POST');
    }

    /**
     * Map the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'PUT');
    }

    /**
     * Map the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($pattern, $handler, 'DELETE');
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
