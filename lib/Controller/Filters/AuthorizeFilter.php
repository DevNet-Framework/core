<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Filters;

use DevNet\System\Async\Tasks\Task;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Authentication\AuthenticationDefaults;

class AuthorizeFilter implements IMiddleware
{
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $authorization = $context->Authorization;

        if ($authorization) {
            $user   = $context->User;
            $policy = $this->options['Policy'] ?? 'Authentication';
            $result = $authorization->Authorize($policy, $user);

            if (!$result->isSucceeded()) {
                if ($policy == 'Authentication') {
                    $authentication = $context->getAttribute('Authentication');
                    $loginPath      = "/account/login";

                    if ($authentication) {
                        $handler   = $authentication->Handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;
                        $loginPath = $handler->Options->LoginPath;
                    }

                    $context->Response->redirect($loginPath);
                } else {
                    $context->Response->setStatusCode(403);
                }

                return Task::completedTask();
            }
        }

        return $next($context);
    }
}
