<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Middleware;

use DevNet\Common\Dependency\IServiceProvider;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\TypeException;
use DevNet\Web\Http\Middleware\IMiddleware;

class MiddlewareFactory
{
    private IServiceProvider $provider;

    public function __construct(IServiceProvider $provider)
    {
        $this->provider = $provider;
    }

    public function create(string $middleware): IMiddleware
    {
        if (is_string($middleware)) {
            if (!class_exists($middleware)) {
                throw new ClassException("Could not find middleware class {$middleware}", 0, 1);
            }

            $interfaceNames = class_implements($middleware);
            if (!in_array(IMiddleware::class, $interfaceNames)) {
                throw new TypeException("Middleware class must implement DevNet\Web\Hosting\IMiddleware", 0, 1);
            }
        }

        return new $middleware($this->provider);
    }
}
