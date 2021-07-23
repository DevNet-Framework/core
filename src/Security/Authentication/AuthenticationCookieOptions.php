<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authentication;

class AuthenticationCookieOptions
{
    const DefaultScheme         = 'AuthenticationCookie';

    public string $CookieName   = 'Devnet-Cookie';
    public string $CookiePath   = '/';
    public string $LoginPath    = '/account/login';
    public string $DeniedPath   = '';
    public int $TimeSpan        = 3600 * 24 * 7;
}
