<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\PropertyTrait;

class AuthorizationPolicy
{
    use PropertyTrait;

    private string $name;
    private array $requirements;

    public function __construct(string $name, array $requirements)
    {
        $this->name = $name;
        $this->requirements = $requirements;
    }

    public function get_Name(): string
    {
        return $this->name;
    }

    public function get_Requirements(): array
    {
        return $this->requirements;
    }
}
