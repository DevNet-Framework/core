<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\Loader\LauncherProperties;
use Closure;

class AuthenticationBuilder
{
    private array $Authentications;

    public function addCookie(string $authenticationSchem, Closure $configuration = null)
    {
        $options = new AuthenticationCookieOptions();
        $options->CookieName .= "-" . md5(LauncherProperties::getWorkspace());

        if ($configuration) {
            $configuration($options);
        }

        $this->Authentications[$authenticationSchem] = new AuthenticationCookieHandler($options);
    }

    public function build()
    {
        return new Authentication($this->Authentications);
    }
}
