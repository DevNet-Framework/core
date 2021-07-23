<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authentication;

use DevNet\Core\Security\ClaimsPrincipal;
use DevNet\Core\Http\Session;
use Exception;

class AuthenticationCookieHandler
{
    private AuthenticationCookieOptions $Options;
    private Session $Session;

    public function __construct(AuthenticationCookieOptions $options)
    {
        $this->Options = $options;
        $this->Session = new Session($options->CookieName);
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function SignIn(ClaimsPrincipal $user, ?bool $isPersistent = null)
    {
        if ($isPersistent) {
            $this->Session->setOptions(['cookie_lifetime' => $this->Options->TimeSpan]);
        } else {
            $this->Session->setOptions(['cookie_lifetime' => 0]);
        }

        $this->Session->start();
        $this->Session->set(ClaimsPrincipal::class, $user);
    }

    public function SignOut()
    {
        $this->Session->destroy();
    }

    public function authenticate(): AuthenticationResult
    {
        if ($this->Session->isSet()) {
            $this->Session->start();
            $principal = $this->Session->get(ClaimsPrincipal::class);

            if ($principal) {
                return new AuthenticationResult($principal);
            }
        }

        return new AuthenticationResult(new Exception("Session cookie dose not have ClaimsPrincipal data"));
    }
}
