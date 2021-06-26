<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Middlewares;

use DevNet\Core\Dispatcher\IMiddleware;
use DevNet\Core\Dispatcher\RequestDelegate;
use DevNet\Core\Security\Authorization\Authorization;
use DevNet\Core\Http\HttpContext;
use DevNet\System\Async\Task;

class AuthorizationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        if ($context->RequestServices->contains(Authorization::class))
        {
            $authorization = $context->RequestServices->getService(Authorization::class);
            $context->addAttribute('Authorization', $authorization);
        }

        return $next($context);
    }
}
