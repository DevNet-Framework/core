<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\PropertyTrait;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use Exception;

class Authentication implements IAuthentication
{
    use PropertyTrait;

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
        $authenticationHandler = $this->handlers[$scheme] ?? null;
        if ($authenticationHandler) {
            if (!$authenticationHandler instanceof IAuthenticationSigningHandler) {
                throw new Exception("The requested authentication handler must be of type IAuthenticationSigningHandler");
            }
        } else {
            foreach ($this->handlers as $handler) {
                if ($handler instanceof IAuthenticationSigningHandler) {
                    $authenticationHandler = $handler;
                    break;
                }
            }
            if (!$authenticationHandler) {
                throw new Exception("No IAuthenticationSigningHandler is registered!");
            }
        }

        $authenticationHandler->signIn($user, $isPersistent);
    }

    public function signOut(?string $scheme = null): void
    {
        $authenticationHandler = $this->handlers[$scheme] ?? null;
        if ($authenticationHandler) {
            if (!$authenticationHandler instanceof IAuthenticationSigningHandler) {
                throw new Exception("The requested authentication handler must be of type IAuthenticationSigningHandler");
            }
        } else {
            foreach ($this->handlers as $handler) {
                if ($handler instanceof IAuthenticationSigningHandler) {
                    $authenticationHandler = $handler;
                    break;
                }
            }
            if (!$authenticationHandler) {
                throw new Exception("No IAuthenticationSigningHandler is registered!");
            }
        }

        $authenticationHandler->signOut();
    }
}
