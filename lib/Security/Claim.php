<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security;

class Claim
{
    public string $Type;
    public string $Value;

    public function __construct(string $Type, string $Value)
    {
        $this->Type = $Type;
        $this->Value = $Value;
    }

    public function __get(string $Name)
    {
        return $this->$Name;
    }
}
