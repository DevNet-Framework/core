<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Dispatcher;

use Artister\DevNet\Dispatcher\IMiddleware;
use Artister\DevNet\Dependency\IServiceProvider;
use Artister\System\Exceptions\ClassException;

class MiddlewareFactory
{
    private IServiceProvider $provider;

    public function __construct(IServiceProvider $provider)
    {
        $this->Provider = $provider;
    }

    public function create(string $middleware) : IMiddleware
    {
        if (is_string($middleware))
        {
            if (!class_exists($middleware))
            {
                throw ClassException::classNotFound($middleware);
            }

            $interfaceNames = class_implements($middleware);

            if (!in_array("Artister\System\Web\Hosting\IMiddleware", $interfaceNames))
            {
                throw new \Exception("invalide type, class must be of type Artister\System\Web\Hosting\IMiddleware");
            }
        }

        return new $middleware($this->Provider);
    }
}