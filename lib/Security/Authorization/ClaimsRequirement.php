<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Task;
use DevNet\System\PropertyTrait;

class ClaimsRequirement implements IAuthorizationRequirement, IAuthorizationHandler
{
    use PropertyTrait;

    protected string $claimType;
    protected array $allowedValues;

    public function __construct(string $claimType, array $allowedValues = [])
    {
        $this->claimType = $claimType;
        $this->allowedValues = $allowedValues;
    }

    public function get_ClaimType(): string
    {
        return $this->claimType;
    }

    public function get_AllowedValues(): array
    {
        return $this->allowedValues;
    }

    public function getHandler(): IAuthorizationHandler
    {
        return $this;
    }

    public function Handle(AuthorizationContext $context): Task
    {
        $user = $context->User;
        if ($user) {
            if ($this->allowedValues) {
                $found = $user->findClaims(fn ($claim) => $claim->Type == $this->claimType
                    && in_array($claim->Value, $this->allowedValues));
            } else {
                $found = $user->findClaims(fn ($claim) => $claim->Type == $this->claimType);
            }

            if ($found) {
                $context->success($this);
            }
        }

        return Task::completedTask();
    }
}
