<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middleware;

use DevNet\Web\Middleware\IMiddleware;
use DevNet\System\Exceptions\ClassException;
use DevNet\System\Dependency\IServiceProvider;

class MiddlewareFactory
{
    private IServiceProvider $provider;

    public function __construct(IServiceProvider $provider)
    {
        $this->Provider = $provider;
    }

    public function create(string $middleware): IMiddleware
    {
        if (is_string($middleware)) {
            if (!class_exists($middleware)) {
                throw ClassException::classNotFound($middleware);
            }

            $interfaceNames = class_implements($middleware);

            if (!in_array("DevNet\Web\Hosting\IMiddleware", $interfaceNames)) {
                throw new \Exception("invalide type, class must be of type DevNet\Web\Hosting\IMiddleware");
            }
        }

        return new $middleware($this->Provider);
    }
}
