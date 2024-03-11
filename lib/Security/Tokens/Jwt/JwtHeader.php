<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Jwt;

use DevNet\Web\Security\Base64UrlEncoder;

class JwtHeader
{
    public string $Alg;
    public string $Typ;
    public ?string $Cty;

    public function __construct(string $algorithm = 'HS256', string $type = 'JWT', ?string $contentType = null)
    {
        $this->Alg = strtoupper($algorithm);
        $this->Typ = strtoupper($type);
        $this->Cty = $contentType;
    }

    public function toJson(): string
    {
        $header = [
            'alg' => $this->Alg,
            'typ' => $this->Typ
        ];

        if ($this->Cty) {
            $header['cty'] = $this->Cty;
        }

        return json_encode($header);
    }

    public function encode(): string
    {
        return Base64UrlEncoder::encode($this->toJson());
    }
}
