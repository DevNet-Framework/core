<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Antiforgery;

class AntiforgeryTokenSet
{
    public ?string $CookieToken;
    public ?string $RequestToken;
    public ?string $FormFieldName;

    public function __construct(string $cookieToken = null, string $requestToken = null, string $formFieldName = null)
    {
        $this->CookieToken = $cookieToken;
        $this->RequestToken = $requestToken;
        $this->FormFieldName = $formFieldName;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}
