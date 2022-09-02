<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Exceptions\TypeException;
use DevNet\Web\Middleware\IMiddleware;

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
