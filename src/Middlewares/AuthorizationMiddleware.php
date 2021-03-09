<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Middlewares;

use Artister\Web\Dispatcher\IMiddleware;
use Artister\Web\Dispatcher\RequestDelegate;
use Artister\Web\Security\Authorization\Authorization;
use Artister\Web\Http\HttpContext;
use Artister\System\Async\Task;

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