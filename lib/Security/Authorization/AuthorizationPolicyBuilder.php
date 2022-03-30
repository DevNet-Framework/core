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

    public function requireAuthentication()
    {
        $requirement = new AuthenticationRequirement();
        $this->requirements[spl_object_id($requirement)] = $requirement;
    }

    public function requireClaim(string $claimType, array $allowedValues = null)
    {
        $requirement = new ClaimRequirement($claimType, $allowedValues);
        $this->requirements[spl_object_id($requirement)] = $requirement;
    }

    public function requireRole(array $roles)
    {
        $requirement = new RoleRequirement($roles);
        $this->requirements[spl_object_id($requirement)] = $requirement;
    }

    public function build()
    {
        return new AuthorizationPolicy($this->name, $this->requirements);
    }
}
