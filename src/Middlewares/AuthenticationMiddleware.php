<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Middlewares;

use Artister\DevNet\Dispatcher\IMiddleware;
use Artister\DevNet\Dispatcher\RequestDelegate;
use Artister\System\Security\Authentication\Authentication;
use Artister\System\Security\ClaimsPrincipal;
use Artister\System\Web\Http\HttpContext;
use Artister\System\Process\Task;

class AuthenticationMiddleware implements IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        if ($context->RequestServices->has(Authentication::class))
        {
            $authentication = $context->RequestServices->getService(Authentication::class);
            $result = $authentication->authenticate();

            $user = $result->isSucceeded() ? $result->Principal : new ClaimsPrincipal();

            $context->addAttribute('Authentication', $authentication);
            $context->addAttribute('User', $user);
        }

        return $next($context);
    }
}