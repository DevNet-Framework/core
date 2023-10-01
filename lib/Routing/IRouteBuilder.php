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
     * Gets the routes added to the builder.
     */
    function get_Routes(): array;

    /**
     * Adds a route that only matches HTTP requests for the given verb, template, and handler
     */
    public function map(string $pattern, string|callable|array $handler, ?string $verb = null): IRouteHandler;

    /**
     * Builds IRouter from the routes specified in the Routes property.
     */
    public function build(): IRouter;
}
