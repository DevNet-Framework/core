<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Claims;

class Claim
{
    public string $Type;
    public string $Value;

    public function __construct(string $Type, string $Value)
    {
        $this->Type = $Type;
        $this->Value = $Value;
    }
}
