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
    private IdentityContext $identityContext;
    private EntitySet $users;

    public function __construct(IdentityContext $identityContext)
    {
        $this->identityContext = $identityContext;
        $this->users = $identityContext->Users;
    }

    public function create(User $user): int
    {
        $result = null;
        preg_match("%" . $this->identityContext->Options->UsernameFormat . "%", $user->Username, $result);

        if (!$result) {
            throw new IdentityException("Username doesn't meet the format requirements: {$this->identityContext->Options->UsernameFormat}");
        }

        $result = null;
        preg_match("%" . $this->identityContext->Options->PasswordFormat . "%", $user->Password, $result);

        if (!$result) {
            throw new IdentityException("Password doesn't meet the format requirements: {$this->identityContext->Options->PasswordFormat}");
        }

        $user->Password = password_hash($user->Password, PASSWORD_DEFAULT);

        $this->users->add($user);
        return $this->identityContext->save();
    }

    public function delete(User $User): int
    {
        $this->users->remove($User);
        return $this->identityContext->save();
    }

    public function update(): int
    {
        return $this->identityContext->save();
    }

    public function getUser(): ?User
    {
        $user  = $this->identityContext->HttpContext->User;
        $claim = $user->findClaim(fn ($claim) => $claim->Type == 'UserId');

        if ($claim) {
            $claim->Value;
            return $this->users->find(intval($claim->Value));
        }

        return null;
    }

    public function isInRole(User $user, string $roleName): bool
    {
        $role = $this->identityContext->Roles->where(fn ($role) => $role->Name == $roleName)->first();
        if (!$role) {
            return false;
        }

        $userId = $user->Id;
        $roleId = $role->Id;
        $userRole = $this->identityContext->UserRole
            ->where(fn ($userRole) => $userRole->UserId == $userId && $userRole->RoleId == $roleId)->first();

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

        $role = $this->identityContext->Roles->where(fn ($role) => $role->Name == $roleName)->first();
        if (!$role) {
            return new IdentityResult(-1);
        }

        $userRole = new UserRole();
        $userRole->UserId = $user->Id;
        $userRole->RoleId = $role->Id;

        $this->identityContext->UserRole->add($userRole);
        $this->identityContext->Save();

        return new IdentityResult(1);
    }

    public function removeFromRole(User $user, string $roleName)
    {
        if (!$this->isInRole($user, $roleName)) {
            return new IdentityResult(0);
        }

        $role = $this->identityContext->Roles->where(fn ($role) => $role->Name == $roleName)->first();
        if (!$role) {
            return new IdentityResult(-1);
        }

        $userId = $user->Id;
        $roleId = $role->Id;
        $userRole = $this->identityContext->UserRole
            ->where(fn ($userRole) => $userRole->UserId == $userId && $userRole->RoleId == $roleId)->first();

        $this->identityContext->UserRole->remove($userRole);
        $this->identityContext->Save();

        return new IdentityResult(1);
    }
}
