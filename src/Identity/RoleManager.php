<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Identity;

use Artister\Data\Entity\EntitySet;

class RoleManager
{
    private IdentityContext $identityContext;
    private EntitySet $Roles;

    public function __construct(IdentityContext $identityContext)
    {
        $this->IdentityContext  = $identityContext;
        $this->Roles            = $identityContext->Roles;
    }

    public function create(Role $role)
    {
        $this->Roles->add($role);
        $this->IdentityContext->save();
    }

    public function delete(Role $role)
    {
        $this->Users->remove($role);
        $this->IdentityContext->save();
    }

    public function update()
    {
        $this->IdentityContext->save();
    }
}