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
use DevNet\Web\Http\Session;
use Exception;

class AuthenticationCookieHandler
{
    private AuthenticationCookieOptions $options;
    private Session $session;

    public function __get(string $name)
    {
        if ($name == 'Options') {
            return $this->options;
        }

        if ($name == 'Session') {
            return $this->session;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(AuthenticationCookieOptions $options)
    {
        $this->options = $options;
        $this->session = new Session($options->CookieName);
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
