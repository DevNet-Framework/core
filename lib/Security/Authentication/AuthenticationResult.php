<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\ObjectTrait;
use DevNet\Web\Security\ClaimsPrincipal;
use Exception;

class AuthenticationResult
{
    use ObjectTrait;
    
    private ?ClaimsPrincipal $principal = null;
    private ?Exception $error = null;

    public function __construct(object $result)
    {
        if ($result instanceof ClaimsPrincipal) {
            $this->principal = $result;
        }

        if ($result instanceof Exception) {
            $this->error = $result;
        }
    }

    public function get_Principal(): ?ClaimsPrincipal
    {
        return $this->principal;
    }

    public function get_Error(): ?Exception
    {
        return $this->error;
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
