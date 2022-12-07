<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
