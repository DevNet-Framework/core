<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
