<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Security\Antiforgery;

class AntiforgeryToken
{
    private string $Value;
    private bool $IsHashed = false;

    public function __construct(string $token = null, string $key = null)
    {
        if ($token)
        {
            if ($key)
            {
                $this->Value = hash_hmac('sha256', $this->Value, $key);
            }
            else
            {
                $this->Value = hash('sha256', $token);
            }

            $this->IsHashed = true;
        }
        else
        {
            $this->Value = bin2hex(random_bytes(32));
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}