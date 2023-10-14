<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Http\Session;

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
