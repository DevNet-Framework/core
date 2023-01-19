<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\ObjectTrait;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use Exception;

class AuthenticationResult
{
    use ObjectTrait;
    
    private ?ClaimsIdentity $identity = null;
    private ?Exception $error = null;

    public function __construct(object $result)
    {
        if ($result instanceof ClaimsIdentity) {
            $this->principal = $result;
        } else if ($result instanceof Exception) {
            $this->error = $result;
        }
    }

    public function get_Identity(): ?ClaimsIdentity
    {
        return $this->identity;
    }

    public function get_Error(): ?Exception
    {
        return $this->error;
    }

    public function isSucceeded(): bool
    {
        return $this->identity ? true : false;
    }

    public function isFailed(): bool
    {
        return $this->error ? true : false;
    }
}
