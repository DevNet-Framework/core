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
use Artister\DevNet\Http\HttpContext;
use Artister\DevNet\Dependency\IServiceProvider;
use Artister\System\Process\Task;
use Artister\System\Security\Authentication\Authentication;
use Artister\System\Security\ClaimsPrincipal;

class AuthenticationMiddleware implements IMiddleware
{
    private IServiceProvider $provider;

    public function __construct(IServiceProvider $provider)
    {
        $this->provider = $provider;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next) : Task
    {
        if ($this->provider->has(Authentication::class))
        {
            $authentication = $this->provider->getService(Authentication::class);
            $result = $authentication->authenticate();

            $user = $result->isSucceeded() ? $result->Principal : new ClaimsPrincipal();

            $context->addAttribute('Authentication', $authentication);
            $context->addAttribute('User', $user);
        }

        return $next($context);
    }
}