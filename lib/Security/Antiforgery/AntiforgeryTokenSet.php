<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Antiforgery;

use DevNet\System\Exceptions\PropertyException;

class AntiforgeryTokenSet
{
    public ?string $cookieToken;
    public ?string $requestToken;
    public ?string $formFieldName;

    public function __get(string $name)
    {
        if (in_array($name, ['CookieToken', 'RequestToken', 'FormFieldName'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(string $cookieToken = null, string $requestToken = null, string $formFieldName = null)
    {
        $this->cookieToken = $cookieToken;
        $this->requestToken = $requestToken;
        $this->formFieldName = $formFieldName;
    }
}
