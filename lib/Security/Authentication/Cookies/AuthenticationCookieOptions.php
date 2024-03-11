<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\Cookies;

use DevNet\System\TimeSpan;

class AuthenticationCookieOptions
{
    public string $CookieName    = "Identity";
    public string $CookiePath    = "/";
    public ?TimeSpan $ExpireTime = null;

    public function __construct(string $cookieName = 'Identity', string $cookiePath = '/', ?TimeSpan $expireTime = null)
    {
        $this->CookieName = $cookieName;
        $this->CookiePath = $cookiePath;

        $this->CookieName = $this->CookieName . "-" . md5($this->CookieName . $_SERVER['DOCUMENT_ROOT']);

        if (!$expireTime) {
            $this->ExpireTime = TimeSpan::fromDays(7);
        }
    }
}
