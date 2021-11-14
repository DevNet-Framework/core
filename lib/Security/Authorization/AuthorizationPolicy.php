<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

class AuthorizationPolicy
{
    private string $Name;
    private array $Requirements;

    public function __construct(string $Name, array $requirements)
    {
        $this->Name = $Name;
        $this->Requirements = $requirements;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}
