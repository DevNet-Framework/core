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
use DevNet\Web\Security\Authentication\IAuthentication;

class AuthenticationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        if ($context->Services->contains(IAuthentication::class)) {
            $authentication = $context->Services->getService(IAuthentication::class);
            foreach ($authentication->Schemes as $scheme) {
                $result = $authentication->authenticate($scheme);
                if ($result->isSucceeded()) {
                    $context->User = $result->Identity;
                    break;
                }
            }
        }

        return $next($context);
    }
}
