<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\Web\Http\HttpContext;

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
