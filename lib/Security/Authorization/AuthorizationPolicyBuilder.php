<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

class AuthorizationPolicyBuilder
{
    private string $name;
    private array $requirements = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addRequirement(IAuthorizationRequirement $requirement): void
    {
        $this->requirements[] = $requirement;
    }

    public function requireAuthentication(): void
    {
        $this->requirements[] = new AuthenticationRequirement();
    }

    public function requireClaim(string $claimType, array $allowedValues = []): void
    {
        $this->requirements[] = new ClaimsRequirement($claimType, $allowedValues);
    }

    public function requireRole(array $roles): void
    {
        $this->requirements[] = new RolesRequirement($roles);
    }

    public function build(): AuthorizationPolicy
    {
        return new AuthorizationPolicy($this->name, $this->requirements);
    }
}
