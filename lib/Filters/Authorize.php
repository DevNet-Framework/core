<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Filters;

use DevNet\System\Tasks\Task;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use DevNet\Web\Security\Authentication\AuthenticationDefaults;
use DevNet\Web\Security\Authorization\AuthenticationException;
use DevNet\Web\Security\Authorization\AuthorizationContext;
use DevNet\Web\Security\Authorization\AuthorizationException;
use DevNet\Web\Security\Authorization\RolesRequirement;
use Attribute;

#[Attribute]
class Authorize implements IMiddleware
{
    private ?string $policy;
    private array $roles;

    public function __construct(?string $policy = null, array $roles = [])
    {
        $this->policy = $policy;
        $this->roles  = $roles;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next)
    {
        // Allow anonymous Authorization
        if (!$this->policy && !$this->roles) {
            return $next($context);
        }

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
                throw new AuthorizationException("Unable to get Authorization service, make sure to register it as a service!");
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
