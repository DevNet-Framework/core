<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Antiforgery;

use DevNet\Core\Http\HttpContext;

class AntiforgeryTokenGenerator
{
    public function generateCookieToken(): AntiforgeryToken
    {
        return new AntiforgeryToken();
    }

    public function generateRequestToken(string $cookieToken): AntiforgeryToken
    {
        return new AntiforgeryToken($cookieToken);
    }

    public function matchTokens(HttpContext $httpContext, $tokens): bool
    {
        $formToken = $httpContext->Request->Form->getValue($tokens->FormFieldName);

        if ($tokens->RequestToken == $formToken) {
            return true;
        }

        return false;
    }
}
