<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

class AuthenticationCookieOptions
{
    public string $AuthenticationScheme = AuthenticationCookieDefaults::AuthenticationScheme;
    public string $CookieName           = AuthenticationCookieDefaults::CookieName;
    public string $CookiePath           = AuthenticationCookieDefaults::CookiePath;
    public string $LoginPath            = AuthenticationCookieDefaults::LoginPath;
    public string $DeniedPath           = '';
    public int $TimeSpan                = AuthenticationCookieDefaults::TimeSpan;
}
