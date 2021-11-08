<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authentication;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Middleware\IMiddleware;
use DevNet\Core\Middleware\RequestDelegate;
use DevNet\Core\Security\Authentication\Authentication;
use DevNet\Core\Security\ClaimsPrincipal;
use DevNet\System\Async\Task;

class AuthenticationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next): Task
    {
        if ($context->RequestServices->contains(Authentication::class)) {
            $authentication = $context->RequestServices->getService(Authentication::class);
            $result = $authentication->authenticate();

            $user = $result->isSucceeded() ? $result->Principal : new ClaimsPrincipal();

            $context->addAttribute('Authentication', $authentication);
            $context->addAttribute('User', $user);
        }

        return $next($context);
    }
}
