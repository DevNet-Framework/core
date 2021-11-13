<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

use DevNet\System\IO\Stream;

class HttpRequest extends HttpMessage
{
    private string $Method;
    private Uri $Uri;
    private Form $Form;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(
        string $method,
        Uri $uri,
        Headers $headers,
        Cookies $cookies,
        Stream $body,
        Form $form
    ) {
        $this->Method  = $method;
        $this->Uri     = $uri;
        $this->Headers = $headers;
        $this->Cookies = $cookies;
        $this->Body    = $body;
        $this->Form    = $form;
        
        $this->setProtocol();
    }
}
