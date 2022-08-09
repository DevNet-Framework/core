<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\MiddlewareDelegate;

class RouteBuilder implements IRouteBuilder
{
    private ?IRouteHandler $defaultHandler;
    private array $routes;
    private string $prefix = '';
    private string $name   = '';
    private array $filters = [];

    public function __construct(?IRouteHandler $defaultHandler = null)
    {
        $this->defaultHandler  = $defaultHandler;
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
        if (is_object($filter instanceof IMiddleware)) {
            $this->filters[] = $filter;
            return $this;
        }

        $this->filters[] = new MiddlewareDelegate($filter);
        return $this;
    }

    /**
     * mape the route
     */
    public function mapRoute(string $name, string $pattern, string ...$target): void
    {
        if ($this->defaultHandler) {
            $routeHandler = clone $this->defaultHandler;
            $routeHandler->Target = $target;
        } else {
            $routeHandler = new RouteHandler($target[0] ?? null, $this->filters);
        }

        $pattern = $this->prefix . '/' . trim($pattern, '/');
        $this->routes[] = new Route($name, 'ANY', $pattern, $routeHandler);
        $this->name = ''; // reset the name for the next route
        $this->filters = []; // reset the filters for the next route
    }

    /**
     * mape the route using Http Verb.
     */
    public function mapVerb(string $verb, string $pattern, $target): void
    {
        $pattern = $this->prefix . '/' . trim($pattern, '/');
        $this->routes[] = new Route($this->name, $verb, $pattern, new RouteHandler($target, $this->filters));
        $this->name = ''; // reset the name for the next route
        $this->filters = []; // reset the filters for the next route
    }

    /**
     * mape the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, $target): void
    {
        $this->mapVerb('GET', $pattern, $target);
    }

    /**
     * mape the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, $target): void
    {
        $this->mapVerb('POST', $pattern, $target);
    }

    /**
     * mape the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, $target): void
    {
        $this->mapVerb('PUT', $pattern, $target);
    }

    /**
     * mape the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, $target): void
    {
        $this->mapVerb('DELETE', $pattern, $target);
    }

    /**
     * build the router and retur RouteCollection instance.
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
