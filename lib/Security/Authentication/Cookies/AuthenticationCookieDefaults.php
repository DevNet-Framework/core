<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\Cookies;

class AuthenticationCookieDefaults
{
    public const AuthenticationScheme = 'Cookies';
    public const CookieName           = 'Devnet-Identity';
    public const CookiePath           = '/';
    public const LoginPath            = '/account/login';
    public const TimeSpan             = 3600 * 24 * 7;
}
