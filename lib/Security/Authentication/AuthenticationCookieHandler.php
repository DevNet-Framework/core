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
use DevNet\Web\Http\Session;
use Exception;

class AuthenticationCookieHandler
{
    use ObjectTrait;

    private AuthenticationCookieOptions $options;
    private Session $session;

    public function __construct(AuthenticationCookieOptions $options)
    {
        $this->options = $options;
        $this->session = new Session($options->CookieName);
    }

    public function get_Options(): AuthenticationCookieOptions
    {
        return $this->options;
    }

    public function get_Session(): Session
    {
        return $this->session;
    }

    public function signIn(ClaimsPrincipal $user, ?bool $isPersistent = null)
    {
        if ($isPersistent) {
            $this->session->setOptions(['cookie_lifetime' => $this->options->TimeSpan]);
        } else {
            $this->session->setOptions(['cookie_lifetime' => 0]);
        }

        $this->session->start();
        $this->session->set(ClaimsPrincipal::class, $user);
    }

    public function signOut()
    {
        $this->session->destroy();
    }

    public function authenticate(): AuthenticationResult
    {
        if ($this->session->isSet()) {
            $this->session->start();
            $principal = $this->session->get(ClaimsPrincipal::class);

            if ($principal) {
                return new AuthenticationResult($principal);
            }
        }

        return new AuthenticationResult(new Exception("Session cookie dose not have ClaimsPrincipal data"));
    }
}
