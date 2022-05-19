<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Security\ClaimsPrincipal;
use Exception;

class Authentication
{
    private array $handlers;

    public function __get(string $name)
    {
        if ($name == 'Handlers') {
            return $this->handlers;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function signIn(ClaimsPrincipal $user, ?bool $isPersistent = null)
    {
        $handler = $this->handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;

        if ($handler) {
            $handler->signIn($user, $isPersistent);
        }
    }

    public function signOut()
    {
        $handler = $this->handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;

        if ($handler) {
            $handler->signOut();
        }
    }

    public function authenticate(): AuthenticationResult
    {
        $handler = $this->handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;

        if ($handler) {
            return $handler->authenticate();
        }

        return new AuthenticationResult(new Exception("Missing Authentication Handler"));
    }
}
