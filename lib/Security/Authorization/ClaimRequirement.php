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

class ClaimRequirement extends AuthorizationHandler implements IAuthorizationRequirement
{
    private string $clamType;
    private ?array $allowedValues;

    public function __get(string $name)
    {
        if ($name == 'ClamType') {
            return $this->clamType;
        }

        if ($name == 'AllowedValues') {
            return $this->allowedValues;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(string $claimType, array $allowedValues = null)
    {
        $this->claimType     = $claimType;
        $this->allowedValues = $allowedValues;
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
                $found = $user->findClaims(fn ($claim) => $claim->Type == $requirement->ClaimType
                    && in_array($claim->Value, $this->allowedValues));
            } else {
                $found = $user->findClaims(fn ($claim) => $claim->Type == $requirement->ClaimType);
            }

            if ($found) {
                $context->success($requirement);
            }
        }

        return Task::completedTask();
    }
}
