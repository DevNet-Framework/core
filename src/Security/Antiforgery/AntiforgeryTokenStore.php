<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Antiforgery;

use DevNet\Core\Http\HttpContext;

class AntiforgeryTokenStore
{
    private AntiforgeryOptions $Options;

    public function __construct(AntiforgeryOptions $options)
    {
        $this->Options = $options;
    }

    public function getCookieToken(HttpContext $httpContext) : ?string
    {
        return $httpContext->Request->Cookies->getValue($this->Options->CookieName);
    }

    public function saveCookieToken(HttpContext $httpContext, string $token) : void
    {
        $httpContext->Response->Cookies->Add($this->Options->CookieName, $token, $this->Options->Cookie);
    }
}
