<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
