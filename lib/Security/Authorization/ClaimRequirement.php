<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Task;

class ClaimRequirement extends AuthorizationHandler implements IAuthorizationRequirement
{
    private string $ClamType;
    private ?array $AllowedValues;

    public function __construct(string $claimType, array $allowedValues = null)
    {
        $this->ClaimType     = $claimType;
        $this->AllowedValues = $allowedValues;
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
        $user =  $context->User;
        if ($user) {
            if ($this->AllowedValues) {
                $found = $user->findClaims(fn ($claim) => $claim->Type == $requirement->ClaimType
                    && in_array($claim->Value, $this->AllowedValues));
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
