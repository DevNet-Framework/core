<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing;

/**
 * Specifies a collection of routes for an application.
 */
interface IRouteBuilder
{
    /**
     * Adds a route that only matches HTTP requests for the given pattern and verb.
     */
    public function map(string $pattern, IRouteHandler $handler, ?string $verb = null): IRouteHandler;

    /**
     * from the specified routes.
     */
    public function build(): IRouter;
}
