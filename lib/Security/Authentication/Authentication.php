<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\ObjectTrait;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use Exception;

class Authentication
{
    use ObjectTrait;

    private array $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function get_Schemes(): array
    {
        return array_keys($this->handlers);
    }

    public function get_Handlers(): array
    {
        return $this->handlers;
    }

    public function authenticate(?string $scheme = null): AuthenticationResult
    {
        // get handler by scheme else get the first handler.
        $handler = $this->handlers[$scheme] ?? reset($this->handlers);

        if ($handler) {
            return $handler->authenticate();
        }

        return new AuthenticationResult(new Exception("The authentication handler is missing!"));
    }

    public function signIn(ClaimsIdentity $user, bool $isPersistent = false, ?string $scheme = null): void
    {
        // get handler by scheme else get the first handler or return false.
        $handler = $this->handlers[$scheme] ?? reset($this->handlers);

        if (!$handler instanceof IAuthenticationSigningHandler) {
            throw new Exception("The authentication signing handler is missing!");
        }

        $handler->signIn($user, $isPersistent);
    }

    public function signOut(?string $scheme = null): void
    {
        // get handler by scheme else get the first handler or return false.
        $handler = $this->handlers[$scheme] ?? reset($this->handlers);

        if (!$handler instanceof IAuthenticationSigningHandler) {
            throw new Exception("The authentication signing handler is missing!");
        }

        $handler->signOut();
    }
}
