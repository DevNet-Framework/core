<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authentication;

use Artister\DevNet\Security\ClaimsPrincipal;
use Exception;

class AuthenticationResult
{
    private ?ClaimsPrincipal $Principal = null;
    private ?Exception $Error = null;

    public function __construct(object $result)
    {
        if ($result instanceof ClaimsPrincipal)
        {
            $this->Principal = $result;
        }

        if ($result instanceof Exception)
        {
            $this->Error = $result;
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function isSucceeded() : bool
    {
        return $this->Principal ? true : false;
    }

    public function isFailed() : bool
    {
        return $this->Error ? true : false;
    }
}