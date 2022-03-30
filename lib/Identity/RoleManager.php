<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Entity\EntitySet;

class RoleManager
{
    private IdentityContext $identityContext;
    private EntitySet $roles;

    public function __construct(IdentityContext $identityContext)
    {
        $this->identityContext = $identityContext;
        $this->roles = $identityContext->Roles;
    }

    public function create(Role $role): int
    {
        $this->roles->add($role);
        return $this->identityContext->save();
    }

    public function delete(Role $role): int
    {
        $this->users->remove($role);
        return $this->identityContext->save();
    }

    public function update(): int
    {
        return $this->identityContext->save();
    }
}
