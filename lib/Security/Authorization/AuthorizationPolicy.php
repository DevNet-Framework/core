<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Exceptions\PropertyException;

class AuthorizationPolicy
{
    private string $name;
    private array $requirements;

    public function __get(string $name)
    {
        if ($name == 'Name') {
            return $this->name;
        }

        if ($name == 'Requirements') {
            return $this->requirements;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(string $name, array $requirements)
    {
        $this->name = $name;
        $this->requirements = $requirements;
    }
}
