<?php

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
        $this->IdentityContext = $identityContext;
        $this->Users           = $identityContext->Users;
    }

    public function create(User $user): int
    {
        $result = null;
        preg_match("%" . $this->IdentityContext->Options->UsernameFormat . "%", $user->Username, $result);

        if (!$result) {
            throw new IdentityException("Username doesn't meet the format requirements: {$this->IdentityContext->Options->UsernameFormat}");
        }

        $result = null;
        preg_match("%" . $this->IdentityContext->Options->PasswordFormat . "%", $user->Password, $result);

        if (!$result) {
            throw new IdentityException("Password doesn't meet the format requirements: {$this->IdentityContext->Options->PasswordFormat}");
        }

        $user->Password = password_hash($user->Password, PASSWORD_DEFAULT);

        $this->Users->add($user);
        return $this->IdentityContext->save();
    }

    public function delete(User $User): int
    {
        $this->Users->remove($User);
        return $this->IdentityContext->save();
    }

    public function update(): int
    {
        return $this->IdentityContext->save();
    }

    public function getUser(): ?User
    {
        $user  = $this->IdentityContext->HttpContext->User;
        $claim = $user->findClaim(fn ($claim) => $claim->Type == 'UserId');

        if ($claim) {
            $claim->Value;
            return $this->Users->find(intval($claim->Value));
        }

        return null;
    }

    public function isInRole(User $user, string $roleName): bool
    {
        $role = $this->IdentityContext->Roles->where(fn ($x) => $x->Name == $roleName)->first();
        if (!$role) {
            return false;
        }

        $userId = $user->Id;
        $roleId = $role->Id;
        $userRole = $this->IdentityContext->UserRole
            ->where(fn ($x) => $x->UserId == $userId && $x->RoleId == $roleId)->first();

        if (!$userRole) {
            return false;
        }

        return true;
    }

    public function addToRole(User $user, string $roleName)
    {
        if ($this->isInRole($user, $roleName)) {
            return new IdentityResult(0);
        }

        $role = $this->IdentityContext->Roles->where(fn ($x) => $x->Name == $roleName)->first();
        if (!$role) {
            return new IdentityResult(-1);
        }

        $userRole = new UserRole();
        $userRole->UserId = $user->Id;
        $userRole->RoleId = $role->Id;

        $this->IdentityContext->UserRole->add($userRole);
        $this->IdentityContext->Save();

        return new IdentityResult(1);
    }

    public function removeFromRole(User $user, string $roleName)
    {
        if (!$this->isInRole($user, $roleName)) {
            return new IdentityResult(0);
        }

        $role = $this->IdentityContext->Roles->where(fn ($x) => $x->Name == $roleName)->first();
        if (!$role) {
            return new IdentityResult(-1);
        }

        $userId = $user->Id;
        $roleId = $role->Id;
        $userRole = $this->IdentityContext->UserRole
            ->where(fn ($x) => $x->UserId == $userId && $x->RoleId == $roleId)->first();

        $this->IdentityContext->UserRole->remove($userRole);
        $this->IdentityContext->Save();

        return new IdentityResult(1);
    }
}
