<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Security\Antiforgery;

use Artister\Web\Http\HttpContext;

class AntiforgeryTokenGenerator
{
    public function generateCookieToken() : AntiforgeryToken
    {
        return new AntiforgeryToken();
    }

    public function generateRequestToken(string $cookieToken) : AntiforgeryToken
    {
        return new AntiforgeryToken($cookieToken);
    }

    public function matchTokens(HttpContext $httpContext, $tokens) : bool
    {
        $formToken = $httpContext->Request->Form->getValue($tokens->FormFieldName);

        if ($tokens->RequestToken == $formToken)
        {
            return true;
        }

        return false;
    }
}