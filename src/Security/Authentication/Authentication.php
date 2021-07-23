<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authentication;

use DevNet\Core\Security\ClaimsPrincipal;
use Exception;

class Authentication
{
    private array $Handlers;

    public function __construct(array $handlers)
    {
        $this->Handlers = $handlers;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function SignIn(ClaimsPrincipal $user, ?bool $isPersistent = null)
    {
        $handler = $this->Handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;

        if ($handler) {
            $handler->SignIn($user, $isPersistent);
        }
    }

    public function SignOut()
    {
        $handler = $this->Handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;

        if ($handler) {
            $handler->SignOut();
        }
    }

    public function authenticate(): AuthenticationResult
    {
        $handler = $this->Handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;

        if ($handler) {
            return $handler->authenticate();
        }

        return new AuthenticationResult(new Exception("Missing Authentication Handler"));
    }
}
