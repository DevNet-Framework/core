<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Security\ClaimsPrincipal;
use Exception;

class AuthenticationResult
{
    private ?ClaimsPrincipal $principal = null;
    private ?Exception $error = null;

    public function __get(string $name)
    {
        if ($name == 'Principal') {
            return $this->principal;
        }

        if ($name == 'Error') {
            return $this->error;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(object $result)
    {
        if ($result instanceof ClaimsPrincipal) {
            $this->principal = $result;
        }

        if ($result instanceof Exception) {
            $this->error = $result;
        }
    }

    public function isSucceeded(): bool
    {
        return $this->principal ? true : false;
    }

    public function isFailed(): bool
    {
        return $this->error ? true : false;
    }
}
