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
    private string $Name;
    private array $Requirements = [];

    public function __construct(string $name)
    {
        $this->Name = $name;
    }

    public function requireAuthentication()
    {
        $requirement = new AuthenticationRequirement();
        $this->Requirements[spl_object_id($requirement)] = $requirement;
    }

    public function requireClaim(string $claimType, array $allowedValues = null)
    {
        $requirement = new ClaimRequirement($claimType, $allowedValues);
        $this->Requirements[spl_object_id($requirement)] = $requirement;
    }

    public function requireRole(array $roles)
    {
        $requirement = new RoleRequirement($roles);
        $this->Requirements[spl_object_id($requirement)] = $requirement;
    }

    public function build()
    {
        return new AuthorizationPolicy($this->Name, $this->Requirements);
    }
}
