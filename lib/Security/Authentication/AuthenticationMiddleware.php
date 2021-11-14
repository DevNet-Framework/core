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
use DevNet\Web\Security\ClaimsPrincipal;
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
