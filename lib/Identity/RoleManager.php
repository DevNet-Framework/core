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
    private EntitySet $Roles;

    public function __construct(IdentityContext $identityContext)
    {
        $this->IdentityContext  = $identityContext;
        $this->Roles            = $identityContext->Roles;
    }

    public function create(Role $role): int
    {
        $this->Roles->add($role);
        return $this->IdentityContext->save();
    }

    public function delete(Role $role): int
    {
        $this->Users->remove($role);
        return $this->IdentityContext->save();
    }

    public function update(): int
    {
        return $this->IdentityContext->save();
    }
}
