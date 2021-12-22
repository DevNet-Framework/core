<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

use DevNet\System\IO\Stream;

abstract class HttpMessage
{
    protected string $Protocol;
    protected Headers $Headers;
    protected Cookies $Cookies;
    protected ?Stream $Body;

    public function setProtocol(string $protocol = null)
    {
        if (!$protocol) {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
        }

        $this->Protocol = $protocol;
    }

    abstract public function __get(string $name);
}
