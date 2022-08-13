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

class ClaimsRequirement implements IAuthorizationRequirement, IAuthorizationHandler
{
    protected string $ClaimType;
    protected array $AllowedValues;

    public function __get(string $name)
    {
        if ($name == 'ClaimType') {
            return $this->ClaimType;
        }

        if ($name == 'AllowedValues') {
            return $this->AllowedValues;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("Access to non-public property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("Access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(string $claimType, array $allowedValues = [])
    {
        $this->ClaimType = $claimType;
        $this->AllowedValues = $allowedValues;
    }

    public function getHandler(): IAuthorizationHandler
    {
        return $this;
    }

    public function Handle(AuthorizationContext $context): Task
    {
        $user = $context->User;
        if ($user) {
            if ($this->AllowedValues) {
                $found = $user->findClaims(fn ($claim) => $claim->Type == $this->ClaimType
                    && in_array($claim->Value, $this->AllowedValues));
            } else {
                $found = $user->findClaims(fn ($claim) => $claim->Type == $this->ClaimType);
            }

            if ($found) {
                $context->success($this);
            }
        }

        return Task::completedTask();
    }
}
