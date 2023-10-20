<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\Cookies;

use DevNet\System\TimeSpan;
use DevNet\System\PropertyTrait;
use DevNet\Web\Http\Session;
use DevNet\Web\Security\Authentication\AuthenticationResult;
use DevNet\Web\Security\Authentication\IAuthenticationHandler;
use DevNet\Web\Security\Authentication\IAuthenticationSigningHandler;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use Exception;

class AuthenticationCookieHandler implements IAuthenticationHandler, IAuthenticationSigningHandler
{
    use PropertyTrait;

    private AuthenticationCookieOptions $options;
    private Session $session;

    public function __construct(AuthenticationCookieOptions $options)
    {
        $this->options = $options;
        $this->session = new Session($options->CookieName);

        if (!$this->options->ExpireTime) {
            $this->options->ExpireTime = new TimeSpan();
        }
    }

    public function get_Options(): AuthenticationCookieOptions
    {
        return $this->options;
    }

    public function get_Session(): Session
    {
        return $this->session;
    }

    public function authenticate(): AuthenticationResult
    {
        if ($this->session->isSet()) {
            $this->session->start();
            $identity = $this->session->get(ClaimsIdentity::class);

            if ($identity) {
                return new AuthenticationResult($identity);
            }
        }

        return new AuthenticationResult(new Exception("Session cookie dose not have ClaimsIdentity data"));
    }

    public function signIn(ClaimsIdentity $user, bool $isPersistent = false): void
    {
        if ($isPersistent) {
            $this->session->setOptions(['cookie_lifetime' => (int) $this->options->ExpireTime->TotalSeconds]);
        } else {
            $this->session->setOptions(['cookie_lifetime' => 0]);
        }

        $this->session->start();
        $this->session->set(ClaimsIdentity::class, $user);
    }

    public function signOut(): void
    {
        $this->session->destroy();
    }
}
