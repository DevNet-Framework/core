<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authorization;

use DevNet\System\Async\Tasks\Task;

class RoleRequirement extends AuthorizationHandler implements IAuthorizationRequirement
{
    private array $AllowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->AllowedRoles = $allowedRoles;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getHandlerName(): string
    {
        return get_class($this);
    }

    public function HandleRequirement(AuthorizationContext $context, IAuthorizationRequirement $requirement): Task
    {
        $user = $context->User;
        if ($user) {
            if ($this->AllowedValues) {
                $found = $user->findClaims(fn ($claim) => $claim->Type == 'Role'
                    && in_array($claim->Value, $this->AllowedValues));
            }

            if ($found) {
                $context->success($requirement);
            }
        }

        return Task::completedTask();
    }
}
