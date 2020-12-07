<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authentication;

class AuthenticationDefaults
{
    const AuthenticationScheme          = 'AuthenticationCookie';

    public string $AuthenticationScheme = 'AuthenticationCookie';
    public string $SignInScheme         = 'AuthenticationCookie';
    public string $ChallengeScheme      = 'AuthenticationCookie';
}