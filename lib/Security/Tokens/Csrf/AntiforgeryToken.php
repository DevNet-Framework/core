<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\PropertyTrait;

class AntiforgeryToken
{
    use PropertyTrait;

    private string $value;

    public function __construct()
    {
        $this->value = bin2hex(random_bytes(32));
    }

    public function get_Value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
