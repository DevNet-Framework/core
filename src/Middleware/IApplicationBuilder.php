<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Middleware;

interface IApplicationBuilder
{
    /**
     * @param IMiddleware | Closure | string $middleware
     */
    public function use($middleware);

    public function build(): RequestDelegate;
}
