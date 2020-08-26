<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Identity;

use Artister\DevNet\Entity\EntitySet;
use Artister\System\Linq;

class UserManager
{
    private IdentityContext $IdentityContext;
    private EntitySet $Users;

    public function __construct(IdentityContext $identityContext)
    {
        $this->IdentityContext  = $identityContext;
        $this->Users            = $identityContext->Users;
    }

    public function create(User $user)
    {
        $user->Password = password_hash($user->Password, PASSWORD_DEFAULT);
        
        $this->Users->add($user);
        $this->IdentityContext->save();
    }

    public function delete(User $User)
    {
        $this->Users->remove($User);
        $this->IdentityContext->save();
    }

    public function update()
    {
        $this->IdentityContext->save();
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