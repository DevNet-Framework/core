<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

class AntiForgeryTokenGenerator
{
    public function generateCookieToken(): AntiForgeryToken
    {
        return new AntiForgeryToken();
    }

    public function generateRequestToken(string $cookieToken): AntiForgeryToken
    {
        return new AntiForgeryToken($cookieToken);
    }
}
