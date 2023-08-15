<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\Cookies;

class AuthenticationCookieOptions
{
    public string $CookieName = "Identity";
    public string $CookiePath = "/";
    public int $ExpireTime    = 3600 * 24 * 7;
}
