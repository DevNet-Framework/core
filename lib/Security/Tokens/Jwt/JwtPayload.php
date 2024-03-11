<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Jwt;

use DevNet\Web\Security\Claims\ClaimsIdentity;
use DevNet\Web\Security\Base64UrlEncoder;

class JwtPayload
{
    public ClaimsIdentity $Claims;

    public function __construct(ClaimsIdentity $claims)
    {
        $this->Claims = $claims;
    }

    public function toJson(): string
    {
        $claims = [];
        foreach ($this->Claims as $claim) {
            if (isset($claims[$claim->Type])) {
                $value = $claims[$claim->Type];
                if (is_array($value)) {
                    $claims[$claim->Type][] = $claim->Value;
                } else {
                    $claims[$claim->Type] = [$value, $claim->Value];
                }
            } else {
                $claims[$claim->Type] = $claim->Value;
            }
        }

        return json_encode($claims);
    }

    public function encode(): string
    {
        return Base64UrlEncoder::encode($this->toJson());
    }
}
