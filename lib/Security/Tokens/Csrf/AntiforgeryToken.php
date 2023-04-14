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
    private bool $isHashed = false;

    public function __construct(string $token = null, string $key = null)
    {
        if ($token) {
            if ($key) {
                $this->value = hash_hmac('sha256', $this->value, $key);
            } else {
                $this->value = hash('sha256', $token);
            }

            $this->isHashed = true;
        } else {
            $this->value = bin2hex(random_bytes(32));
        }
    }

    public function get_Value(): string
    {
        return $this->value;
    }

    public function get_IsHashed(): bool
    {
        return $this->isHashed;
    }
}
