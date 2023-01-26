<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\Web\Security\Claims\ClaimsIdentity;

interface IAuthenticationSigningHandler
{
    public function signIn(ClaimsIdentity $user, bool $isPersistent = false): void;

    public function signOut(): void;
}
