<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Hosting;

use DevNet\Web\Middleware\RequestDelegate;

interface IApplicationBuilder
{
    /**
     * @param IMiddleware | Closure | string $middleware
     */
    public function use(callable $middleware): void;

    public function build(): RequestDelegate;
}
