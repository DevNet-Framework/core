<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
