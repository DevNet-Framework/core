<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Http\HttpContext;

class AntiforgeryTokenStore
{
    private AntiforgeryOptions $options;

    public function __construct(AntiforgeryOptions $options)
    {
        $this->options = $options;
    }

    public function getCookieToken(HttpContext $httpContext): ?string
    {
        return $httpContext->Request->Cookies->getValue($this->options->CookieName);
    }

    public function saveCookieToken(HttpContext $httpContext, string $token): void
    {
        $httpContext->Response->Cookies->Add($this->options->CookieName, $token, $this->options->Cookie);
    }
}
