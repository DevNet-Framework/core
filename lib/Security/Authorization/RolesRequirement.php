<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Security\ClaimType;

class RolesRequirement extends ClaimsRequirement
{
    public function __get(string $name)
    {
        if ($name == 'AllowedRoles') {
            return $this->AllowedValues;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("Access to non-public property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("Access to undefined property " . get_class($this) . "::" . $name);
    }
    
    public function __construct(array $allowedRoles)
    {
        if (!$allowedRoles) {
            throw new AuthorizationException("Roles requirement must have at least one allowed role value");
        }

        parent::__construct(ClaimType::Role, $allowedRoles);
    }
}
