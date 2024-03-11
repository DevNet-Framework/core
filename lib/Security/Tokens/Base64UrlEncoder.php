<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens;

class Base64UrlEncoder
{
    /**
     * Encodes data with base64url
     */
    public static function encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decodes data encoded with base64url
     */
    public static function decode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
