<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Authorization\Authorization;
use DevNet\System\Async\Task;

class AuthorizationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next): Task
    {
        if ($context->RequestServices->contains(Authorization::class)) {
            $authorization = $context->RequestServices->getService(Authorization::class);
            $context->addAttribute('Authorization', $authorization);
        }

        return $next($context);
    }
}