<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Security\Session;

class AntiforgeryTokenStore
{
    private Session $session;

    public function __construct(AntiforgeryOptions $options)
    {
        $this->session = new Session($options->CookieName);
    }

    public function saveCookieToken(AntiforgeryToken $token): void
    {
        $this->session->start();
        $this->session->set(AntiforgeryToken::class, $token);
    }

    public function getCookieToken(): ?AntiforgeryToken
    {
        $this->session->start();
        return $this->session->get(AntiforgeryToken::class);
    }
}
