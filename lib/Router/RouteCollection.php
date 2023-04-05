<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

/**
 * Collection of routes
 */
class RouteCollection implements IRouter
{
    private array $routes = [];

    /**
     * add a route object to the array container
     */
    public function add(Route $route): void
    {
        if ($route->Name != '') {
            $this->routes[$route->Name] = $route;
        } else {
            $this->routes[] = $route;
        }
    }

    public function getRoute(string $name): IRouter
    {
        return $this->routes[$name];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function matchRoute(RouteContext $routeContext): bool
    {
        $routeContext->RouteData->Routers[] = $this;

        foreach ($this->routes as $route) {
            if ($route->matchRoute($routeContext)) {
                return true;
            }
        }

        return false;
    }

    public function getRoutePath(RoutePathContext $routePathContext): string
    {
        $RouteName = $routePathContext->getRouteName();

        if (isset($this->routes[$RouteName])) {
            $route = $this->routes[$RouteName];
            return $route->getRoutePath($routePathContext);
        }

        throw new \Exception("invalide route name");
    }
}
