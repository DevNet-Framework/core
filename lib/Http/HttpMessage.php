<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Exceptions\PropertyException;
use DevNet\System\IO\Stream;

abstract class HttpMessage
{
    protected string $Protocol = 'HTTP/1.0';
    protected Headers $Headers;
    protected Cookies $Cookies;
    protected ?Stream $Body;

    public function __get(string $name)
    {
        if (in_array($name, ['Protocol', 'Headers', 'Cookies', 'Body'])) {
            return $this->$name;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function setProtocol(string $protocol)
    {
        $this->Protocol = $protocol;
    }
}
