<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\ObjectTrait;
use DevNet\Web\Security\Claims\ClaimsPrincipal;
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

    public function signIn(ClaimsPrincipal $user, bool $isPersistent = false, ?string $scheme = null)
    {
        // get handler by scheme else get the first handler or return false.
        $handler = $this->handlers[$scheme] ?? reset($this->handlers);

        if ($handler) {
            $handler->signIn($user, $isPersistent);
        }
    }

    public function signOut(?string $scheme = null)
    {
        // get handler by scheme else get the first handler or return false.
        $handler = $this->handlers[$scheme] ?? reset($this->handlers);

        if ($handler) {
            $handler->signOut();
        }
    }

    public function authenticate(?string $scheme = null): AuthenticationResult
    {
        // get handler by scheme else get the first handler or return false.
        $handler = $this->handlers[$scheme] ?? reset($this->handlers);

        if ($handler) {
            return $handler->authenticate();
        }

        return new AuthenticationResult(new Exception("Missing Authentication Handler"));
    }
}
