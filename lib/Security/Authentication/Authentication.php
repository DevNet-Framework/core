<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\ObjectTrait;
use DevNet\Web\Security\ClaimsPrincipal;
use Exception;

class Authentication
{
    use ObjectTrait;

    private array $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function get_Handlers(): array
    {
        return $this->handlers;
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
