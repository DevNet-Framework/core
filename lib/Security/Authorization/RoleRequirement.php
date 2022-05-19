<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Tasks\Task;
use DevNet\System\Exceptions\PropertyException;

class RoleRequirement extends AuthorizationHandler implements IAuthorizationRequirement
{
    private array $allowedRoles;

    public function __get(string $name)
    {
        if ($name == 'AllowedRoles') {
            return $this->allowedRoles;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function getHandlerName(): string
    {
        return get_class($this);
    }

    public function HandleRequirement(AuthorizationContext $context, IAuthorizationRequirement $requirement): Task
    {
        $user = $context->User;
        if ($user) {
            if ($this->allowedValues) {
                $found = $user->findClaims(fn ($claim) => $claim->Type == 'Role'
                    && in_array($claim->Value, $this->allowedValues));
            }

            if ($found) {
                $context->success($requirement);
            }
        }

        return Task::completedTask();
    }
}
