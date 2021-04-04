<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Middlewares;

use DevNet\Web\Dispatcher\IMiddleware;
use DevNet\Web\Dispatcher\RequestDelegate;
use DevNet\Web\Security\Authorization\Authorization;
use DevNet\Web\Http\HttpContext;
use DevNet\System\Async\Task;

class AuthorizationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        if ($context->RequestServices->has(Authorization::class))
        {
            $authorization = $context->RequestServices->getService(Authorization::class);
            $context->addAttribute('Authorization', $authorization);
        }

        return $next($context);
    }
}
