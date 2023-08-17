<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\JwtBearer;

class JwtBearerOptions
{
    public ?string $SecurityKey = null;
    public ?string $Issuer = null;
    public ?string $Audience = null;

    public function __construct(
        ?string $securityKey = null,
        ?string $issuer = null,
        ?string $audience = null
    ) {
        $this->SecurityKey = $securityKey;
        $this->Issuer = $issuer;
        $this->Audience = $audience;
    }
}
