<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
