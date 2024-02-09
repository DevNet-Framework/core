<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
