<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use Closure;

class RouteBuilder implements IRouteBuilder
{
    private ?IRouteHandler $routeHandler;
    private string $prefix = '';
    private array $routes  = [];

    public function __construct(?IRouteHandler $routeHandler = null)
    {
        $this->routeHandler  = $routeHandler;
    }

    /**
     * set the route prefix.
     */
    public function group(string $prefix, callable $callback): void
    {
        $this->prefix = trim($prefix, '/');
        $callback($this);
        // reset the name for the routes outside the group.
        $this->prefix = '';
    }

    /**
     * mape the route.
     */
    public function map(string $pattern, callable|string $handler = null): void
    {
        if ($this->routeHandler && (!$handler instanceof Closure)) {
            $routeHandler = clone $this->routeHandler;
            $routeHandler->Target = $handler;
        } else {
            $routeHandler = new RouteHandler($handler);
        }

        $pattern = $this->prefix . '/' . trim($pattern, '/');
        $this->routes[] = new Route('ANY', $pattern, $routeHandler);
    }

    /**
     * mape the route using Http Verb.
     */
    public function mapVerb(string $verb, string $pattern, callable $handler): IRouteHandler
    {
        $pattern        = $this->prefix . '/' . trim($pattern, '/');
        $routeHandler   = new RouteHandler($handler);
        $this->routes[] = new Route($verb, $pattern, $routeHandler);

        return $routeHandler;
    }

    /**
     * mape the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapVerb('GET', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapVerb('POST', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapVerb('PUT', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, callable $handler): IRouteHandler
    {
        return $this->mapVerb('DELETE', $pattern, $handler);
    }

    /**
     * build the router and return RouteCollection instance.
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
