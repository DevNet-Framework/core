<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Antiforgery;

use DevNet\System\Exceptions\PropertyException;

class AntiforgeryToken
{
    private string $value;
    private bool $isHashed = false;

    public function __get(string $name)
    {
        if ($name == 'Value') {
            return $this->value;
        }

        if ($name == 'IsHashed') {
            return $this->isHashed;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

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
}
