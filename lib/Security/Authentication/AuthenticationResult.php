<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication;

use DevNet\System\PropertyTrait;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use Exception;

class AuthenticationResult
{
    use PropertyTrait;
    
    private ?ClaimsIdentity $identity = null;
    private ?Exception $error = null;

    public function __construct(object $result)
    {
        if ($result instanceof ClaimsIdentity) {
            $this->identity = $result;
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
