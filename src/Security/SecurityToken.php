<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Security;

class SecurityToken
{
    private string $token;

    public function __construct(string $token = null)
    {
        if ($token == null)
        {
            $token = bin2hex(random_bytes(16));
        }

        $this->token = $token;
    }

    public function getValue() : string
    {
        return $this->token;
    }

    public function getHash(string $securityKey = null) : string
    {
        if ($securityKey == null)
        {
            return hash('sha256', $this->token);
        }

        return hash_hmac('sha256', $this->token, $securityKey);
    }
}