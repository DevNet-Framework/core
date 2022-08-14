<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Tasks\Task;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Authentication\AuthenticationDefaults;

class AuthorizeFilter implements IMiddleware
{
    private ?string $policy;
    private array $roles;

    public function __construct(array $options = [])
    {
        $this->policy = $options['policy'] ?? null;
        $this->roles  = $options['roles'] ?? [];
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        $user = $context->User;
        if (!$user->isAuthenticated()) {
            if (!$context->Authentication) {
                throw new AuthenticationException("the authentication service missing or not handled by the AuthenticationMiddleware!", 401);
            }

            $handler = $context->Authentication->Handlers[AuthenticationDefaults::AuthenticationScheme] ?? null;
            $loginPath = $handler->Options->LoginPath;

            $context->Response->redirect($loginPath);
            return Task::completedTask();
        }

        if ($this->policy) {
            $authorization = $context->RequestServices->getService(Authorization::class);
            if (!$authorization) {
                throw new AuthorizationException("The Authorization service is missing!");
            }

            $result = $authorization->Authorize($user, $this->policy);
            if (!$result->isSucceeded()) {
                throw new AuthorizationException("Current user claims do not meet the authorization policy required!", 403);
                return Task::completedTask();
            }
        }

        if ($this->roles) {
            $requirement = new RolesRequirement($this->roles);
            $autorizeContext = new AuthorizationContext([$requirement], $user);
            $requirement->getHandler()->handle($autorizeContext)->wait();
            $result = $autorizeContext->getResult();
            if (!$result->isSucceeded()) {
                throw new AuthorizationException("Current user claims do not meet the authorization roles required!", 403);
                return Task::completedTask();
            }
        }

        return $next($context);
    }
}
