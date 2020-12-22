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
use Artister\Web\Security\Authentication\Authentication;
use Artister\Web\Security\ClaimsPrincipal;
use Artister\Web\Http\HttpContext;
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