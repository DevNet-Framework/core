<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Web\Security\ClaimsPrincipal;
use DevNet\Web\Security\ClaimsIdentity;
use DevNet\Web\Security\Claim;
use DevNet\System\Linq;

class IdentityManager
{
    private IdentityContext $IdentityContext;

    public function __construct(IdentityContext $identityContext)
    {
        $this->IdentityContext = $identityContext;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function signIn(string $username, string $password, bool $isPersistent = false): IdentityResult
    {
        $users = $this->IdentityContext->Users;
        $user  = $users->where(fn ($User) => $User->Username == $username)->first();

        if (!$user) {
            return new IdentityResult(-1);
        }

        $passwordHash = $user->Password;

        if (!password_verify($password, $passwordHash)) {
            return new IdentityResult(-2);
        }

        $identity = new ClaimsIdentity('IdentityUser');
        $identity->addClaim(new Claim('UserId', strval($user->Id)));

        $userPrincipale = new ClaimsPrincipal($identity);
        $this->IdentityContext->HttpContext->Authentication->signIn($userPrincipale, $isPersistent);

        return new IdentityResult(1);
    }

    public function signOut()
    {
        $this->IdentityContext->HttpContext->Authentication->signOut();
    }

    public function isSignedIn(): bool
    {
        $user = $this->IdentityContext->HttpContext->User;
        if ($user) {
            if ($user->isAuthenticated()) {
                return true;
            }
        }

        return false;
    }
}
