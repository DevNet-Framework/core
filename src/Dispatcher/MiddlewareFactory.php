<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Dispatcher;

use DevNet\Core\Dispatcher\IMiddleware;
use DevNet\Core\Dependency\IServiceProvider;
use DevNet\System\Exceptions\ClassException;

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

            if (!in_array("DevNet\Core\Hosting\IMiddleware", $interfaceNames)) {
                throw new \Exception("invalide type, class must be of type DevNet\Core\Hosting\IMiddleware");
            }
        }

        return new $middleware($this->Provider);
    }
}
