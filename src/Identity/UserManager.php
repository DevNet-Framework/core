<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Entity\EntitySet;
use DevNet\System\Linq;

class UserManager
{
    private IdentityContext $IdentityContext;
    private EntitySet $Users;

    public function __construct(IdentityContext $identityContext)
    {
        $this->IdentityContext  = $identityContext;
        $this->Users            = $identityContext->Users;
    }

    public function create(User $user) : int
    {
        $user->Password = password_hash($user->Password, PASSWORD_DEFAULT);
        
        $this->Users->add($user);
        return $this->IdentityContext->save();
    }

    public function delete(User $User) : int
    {
        $this->Users->remove($User);
        return $this->IdentityContext->save();
    }

    public function update() : int
    {
        return $this->IdentityContext->save();
    }

    public function getUser() : ?User
    {
        $user = $this->IdentityContext->HttpContext->User;
        $claim = $user->findClaim(fn($claim) => $claim->Type == 'UserId');

        if ($claim)
        {
            $claim->Value;
            return $this->Users->find(intval($claim->Value));
        }
        
        return null;
    }

    public function isInRole(User $user, string $roleName) : bool
    {
        $role = $this->IdentityContext->Roles->where(fn($x) => $x->Name == $roleName)->first();
        if (!$role)
        {
            return false;
        }

        $userId = $user->Id;
        $roleId = $role->Id;
        $userRole = $this->IdentityContext->UserRole
            ->where(fn($x) => $x->UserId == $userId && $x->RoleId == $roleId)->first();

        if (!$userRole)
        {
            return false;
        }

        return true;
    }

    public function addToRole(User $user, string $roleName)
    {
        if ($this->isInRole($user, $roleName))
        {
            return new IdentityResult(1);
        }

        $role = $this->IdentityContext->Roles->where(fn($x) => $x->Name == $roleName)->first();
        $userRole = new UserRole();
        $userRole->UserId = $user->Id;
        $userRole->RoleId = $role->Id;

        $this->IdentityContext->UserRole->add($userRole);
        $this->IdentityContext->Save();

        return new IdentityResult();
    }

    public function removeFromRole(User $user, string $roleName)
    {
        if (!$this->isInRole($user, $roleName))
        {
            return new IdentityResult(1);
        }

        $role = $this->IdentityContext->Roles->where(fn($x) => $x->Name == $roleName)->first();
        $userId = $user->Id;
        $roleId = $role->Id;
        $userRole = $this->IdentityContext->UserRole
            ->where(fn($x) => $x->UserId == $userId && $x->RoleId == $roleId)->first();

        $this->IdentityContext->UserRole->remove($userRole);
        $this->IdentityContext->Save();

        return new IdentityResult();
    }
}
