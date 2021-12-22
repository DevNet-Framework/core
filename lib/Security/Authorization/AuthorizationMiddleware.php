<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authorization;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Middleware\IMiddleware;
use DevNet\Core\Middleware\RequestDelegate;
use DevNet\Core\Security\Authorization\Authorization;

class AuthorizationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        if ($context->RequestServices->contains(Authorization::class)) {
            $authorization = $context->RequestServices->getService(Authorization::class);
            $context->addAttribute('Authorization', $authorization);
        }

        return $next($context);
    }
}
