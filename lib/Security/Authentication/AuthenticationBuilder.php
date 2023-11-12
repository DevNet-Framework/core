<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Security\Authentication\JwtBearer\JwtBearerHandler;
use DevNet\Web\Security\Authentication\JwtBearer\JwtBearerOptions;
use DevNet\Web\Security\Authentication\Cookies\AuthenticationCookieHandler;
use DevNet\Web\Security\Authentication\Cookies\AuthenticationCookieOptions;
use Closure;

class AuthenticationBuilder
{
    private array $authentications;
    private HttpContext $httpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
    }

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

        $this->authentications[$authenticationScheme] = new JwtBearerHandler($this->httpContext, $options);
    }

    public function build(): Authentication
    {
        return new Authentication($this->authentications);
    }
}
