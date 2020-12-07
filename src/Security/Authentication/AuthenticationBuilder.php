<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authentication;

use Closure;

class AuthenticationBuilder
{
    private array $Authentications;

    public function addCookie(string $AuthenticationSchem, Closure $configuration = null)
    {
        $options = new AuthenticationCookieOptions();
        
        if ($configuration)
        {
            $configuration($options);
        }

        $this->Authentications[$AuthenticationSchem] = new AuthenticationCookieHandler($options);
    }

    public function build()
    {
        return new Authentication($this->Authentications);
    }
}