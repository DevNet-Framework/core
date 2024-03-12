<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

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
        $this->routes[] = $route;
    }

    public function getRoute(string $name): IRouter
    {
        return $this->routes[$name];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function match(RouteContext $routeContext): bool
    {
        $routeContext->RouteData->Routers[] = $this;

        foreach ($this->routes as $route) {
            if ($route->match($routeContext)) {
                return true;
            }
        }

        return false;
    }
}
