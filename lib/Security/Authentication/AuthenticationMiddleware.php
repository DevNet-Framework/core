<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Authentication\Authentication;
use DevNet\Web\Security\Claims\ClaimsIdentity;

class AuthenticationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        if ($context->Services->contains(Authentication::class)) {
            $authentication = $context->Services->getService(Authentication::class);
            $user = new ClaimsIdentity();
            foreach ($authentication->Schemes as $scheme) {
                $result = $authentication->authenticate($scheme);
                if ($result->isSucceeded()) {
                    $user = $result->Identity;
                    break;
                }
            }

            $context->addAttribute('Authentication', $authentication);
            $context->addAttribute('User', $user);
        }

        return $next($context);
    }
}
