<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

 namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Task;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\ActionDelegate;
use DevNet\Web\Endpoint\IActionFilter;
use DevNet\Web\Security\Authentication\AuthenticationException;
use DevNet\Web\Security\Authorization\AuthenticationRequirement;
use DevNet\Web\Security\Authorization\AuthorizationContext;
use DevNet\Web\Security\Authorization\AuthorizationException;
use DevNet\Web\Security\Authorization\IAuthorization;
use DevNet\Web\Security\Authorization\RolesRequirement;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use Attribute;

#[Attribute]
class Authorize implements IActionFilter
{
    private ?string $scheme;
    private ?string $policy;
    private array $roles;

    public function __construct(?string $scheme = null, ?string $policy = null, array $roles = [])
    {
        $this->scheme = $scheme;
        $this->policy = $policy;
        $this->roles  = $roles;
    }

    public function __invoke(ActionContext $context, ActionDelegate $next): Task
    {
        // Allow anonymous authentication
        if ($this->scheme == "Anonymous") {
            return $next($context);
        }

        $user = $context->HttpContext->User;
        if ($this->scheme && $this->scheme != $user->AuthenticationType) {
            // Sets unauthenticated user for unmatched scheme only for this filter.
            $user = new ClaimsIdentity();
        }

        $authorization = $context->HttpContext->Services->getService(IAuthorization::class);
        if (!$authorization) {
            throw new AuthorizationException("Unable to get the Authorization service, make sure if it's already registered!");
        }

        $result = $authorization->authorize($user, $this->policy);
        if (!$result->IsSucceeded) {
            $requirement = $result->FailedRequirements[0] ?? null;
            if ($requirement instanceof AuthenticationRequirement) {
                throw new AuthenticationException("The current user is not authenticated!", 401);
            }

            throw new AuthorizationException("The current user does not meet the required authorization policy!", 403);
        }

        if ($this->roles) {
            $requirement = new RolesRequirement($this->roles);
            $authorizeContext = new AuthorizationContext([$requirement], $user);
            $requirement->getHandler()->handle($authorizeContext)->wait();
            $result = $authorizeContext->getResult();
            if (!$result->IsSucceeded) {
                throw new AuthorizationException("The current user does not meet the required authorization roles!", 403);
            }
        }

        return $next($context);
    }
}
