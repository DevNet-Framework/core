<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Jwt;

use DevNet\Web\Security\Claims\Claim;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use DateTime;

class JwtSecurityToken
{
    public JwtHeader $Header;
    public JwtPayload $Payload;

    public function __construct(ClaimsIdentity $claims, string $algorithm = 'HS256', ?DateTime $expires = null)
    {
        $this->Header = new JwtHeader($algorithm);
        $this->Payload = new JwtPayload($claims);
        if ($expires) {
            $this->Payload->Claims->addClaim(new Claim('exp', $expires->getTimestamp()));
        }
    }

    public function toString(): string
    {
        return $this->Header->encode() . '.' . $this->Payload->encode();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
