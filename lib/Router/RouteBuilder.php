<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use Closure;

class RouteBuilder implements IRouteBuilder
{
    use MethodTrait;
    use PropertyTrait;

    private string $prefix = '';
    private array $routes = [];
    private ?IRouteHandler $routeHandler;

    public function __construct(?IRouteHandler $routeHandler = null)
    {
        $this->routeHandler = $routeHandler;
    }

    function get_Routes(): array
    {
        return $this->routes;
    }

    /**
     * set the route prefix.
     */
    public function mapGroup(string $prefix, callable $callback): void
    {
        $this->prefix = trim($prefix, '/');
        $callback($this);
        // clear the prefix for the routes outside the group.
        $this->prefix = '';
    }

    /**
     * mape the route.
     */
    public function mapRoute(string $pattern, string|callable|array $handler = null): void
    {
        if ($this->routeHandler && (!$handler instanceof Closure)) {
            $routeHandler = clone $this->routeHandler;
            $routeHandler->Target = $handler;
        } else {
            $routeHandler = new RouteHandler($handler);
        }

        $pattern = $this->prefix . '/' . trim($pattern, '/');
        $this->routes[] = new Route($routeHandler, $pattern);
    }

    /**
     * mape the route using Http Verb.
     */
    public function mapVerb(string $verb, string $pattern, string|callable|array $handler): IRouteHandler
    {
        $pattern        = $this->prefix . '/' . trim($pattern, '/');
        $routeHandler   = new RouteHandler($handler);
        $this->routes[] = new Route($routeHandler, $pattern, $verb);

        return $routeHandler;
    }

    /**
     * mape the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapVerb('GET', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapVerb('POST', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, string|callable|array $handler): IRouteHandler
    {
        return $this->mapVerb('PUT', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, string|callable|array $handler): IRouteHandler
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
