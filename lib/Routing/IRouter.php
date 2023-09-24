<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

interface IRouter
{
    /**
     * match the routes against the the HTTP Request, (url path and http method)
     */
    public function match(RouteContext $routeContext): bool;
}
