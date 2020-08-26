<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Router;

use Artister\DevNet\Router\Internal\RouteLogger;

/**
 * Collection of routes
 */
class RouteCollection implements IRouter
{
    private array $Routes;

    /**
     * add a route object to the array container
     */
    public function add(Route $route) : void
    {
        if ($route->Name != '')
        {
            $this->Routes[$route->Name] = $route;
        }
        else
        {
            $this->Routes[] = $route;
        }
    }

    public function getRoute(string $name) : IRouter
    {
        return $this->Routes[$name];
    }

    public function getRoutes() : array
    {
        return $this->Routes;
    }

    public function matchRoute(RouteContext $routeContext) : bool
    {
        $routeContext->RouteData->Routers[] = $this;

        foreach ($this->Routes as $route)
        {
            if ($route->matchRoute($routeContext))
            {
                return true;
            }
        }

        return false;
    }

    public function getRoutePath(RoutePathContext $routePathContext) : string
    {
        $RouteName = $routePathContext->getRouteName();

        if (isset($this->Routes[$RouteName]))
        {
            $route = $this->Routes[$RouteName];
            return $route->generatePath($routePathContext);
        }

        throw new \Exception("invalide route name");
    }
}