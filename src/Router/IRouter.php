<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Router;

interface IRouter
{
    /**
     * match the routes against the the HTTP Request, (url path and http method)
     */
    public function matchRoute(RouteContext $routeContext) : bool;

    /**
     * generate url from routeCollection based on the route name and parameters
     */
    public function getRoutePath(RoutePathContext $routePathContext) : string;
}