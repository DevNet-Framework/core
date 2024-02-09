<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\Cookies;

use DevNet\System\Runtime\LauncherProperties;
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

        if ($this->CookieName == 'Identity') {
            $this->CookieName .= "-" . md5(LauncherProperties::getRootDirectory());
        }

        if (!$expireTime) {
            $this->ExpireTime = TimeSpan::fromDays(7);
        }
    }
}
