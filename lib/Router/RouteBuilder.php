<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\Web\Action\ActionFilterDelegate;
use DevNet\Web\Action\IActionFilter;
use Closure;

class RouteBuilder implements IRouteBuilder
{
    private ?IRouteHandler $routeHandler;
    private string $prefix = '';
    private string $name   = '';
    private array $routes  = [];
    private array $filters = [];

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

        // reset the name for the next route
        $this->prefix = '';
    }

    /**
     * set route name
     */
    public function name(string $name): RouteBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function addFilter(callable $filter): RouteBuilder
    {
        if (is_object($filter instanceof IActionFilter)) {
            $this->filters[] = $filter;
            return $this;
        }

        $this->filters[] = new ActionFilterDelegate($filter);
        return $this;
    }

    /**
     * mape the route
     */
    public function mapRoute(string $pattern, string|callable $handler = null): void
    {
        if ($this->routeHandler && (!$handler instanceof Closure)) {
            $routeHandler = clone $this->routeHandler;
            $routeHandler->Target = $handler;
        } else {
            $routeHandler = new RouteHandler($handler, $this->filters);
        }

        $pattern = $this->prefix . '/' . trim($pattern, '/');
        $this->routes[] = new Route($this->name, 'ANY', $pattern, $routeHandler);
        $this->name = ''; // reset the name for the next route
        $this->filters = []; // reset the filters for the next route
    }

    /**
     * mape the route using Http Verb.
     */
    public function mapVerb(string $verb, string $pattern, callable $handler): void
    {
        $pattern = $this->prefix . '/' . trim($pattern, '/');
        $this->routes[] = new Route($this->name, $verb, $pattern, new RouteHandler($handler, $this->filters));
        $this->name = ''; // reset the name for the next route
        $this->filters = []; // reset the filters for the next route
    }

    /**
     * mape the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, callable $handler): void
    {
        $this->mapVerb('GET', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, callable $handler): void
    {
        $this->mapVerb('POST', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, callable $handler): void
    {
        $this->mapVerb('PUT', $pattern, $handler);
    }

    /**
     * mape the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, callable $handler): void
    {
        $this->mapVerb('DELETE', $pattern, $handler);
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
