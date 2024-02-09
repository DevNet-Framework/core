<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\Web\Security\Claims\ClaimsIdentity;

interface IAuthentication
{
    public function authenticate(?string $scheme = null): AuthenticationResult;

    public function signIn(ClaimsIdentity $user, bool $isPersistent = false, ?string $scheme = null): void;

    public function signOut(?string $scheme = null): void;
}
