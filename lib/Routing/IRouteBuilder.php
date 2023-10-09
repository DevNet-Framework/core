<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
