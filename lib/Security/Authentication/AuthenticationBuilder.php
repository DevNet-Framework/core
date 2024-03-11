<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\Web\Security\Authentication\JwtBearer\JwtBearerHandler;
use DevNet\Web\Security\Authentication\JwtBearer\JwtBearerOptions;
use DevNet\Web\Security\Authentication\Cookies\AuthenticationCookieHandler;
use DevNet\Web\Security\Authentication\Cookies\AuthenticationCookieOptions;
use Closure;

class AuthenticationBuilder
{
    private array $authentications;

    public function addCookie(string $authenticationScheme = AuthenticationScheme::CookieSession, Closure $configuration = null): void
    {
        $options = new AuthenticationCookieOptions();
        if ($configuration) {
            $configuration($options);
        }

        $this->authentications[$authenticationScheme] = new AuthenticationCookieHandler($options);
    }

    public function addJwtBearer(string $authenticationScheme = AuthenticationScheme::JwtBearer, Closure $configuration = null): void
    {
        $options = new JwtBearerOptions();
        if ($configuration) {
            $configuration($options);
        }

        $this->authentications[$authenticationScheme] = new JwtBearerHandler($options);
    }

    public function build(): Authentication
    {
        return new Authentication($this->authentications);
    }
}
