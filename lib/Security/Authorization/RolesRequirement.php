<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

class RolesRequirement extends ClaimsRequirement
{    
    public function __construct(array $allowedRoles)
    {
        if (!$allowedRoles) {
            throw new AuthorizationException("Roles requirement must have at least one allowed role value");
        }

        parent::__construct("role", $allowedRoles);
    }
}
