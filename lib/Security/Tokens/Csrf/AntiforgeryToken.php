<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
