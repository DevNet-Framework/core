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
    private ?Form $Form;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(
        string $method,
        string $uri,
        array $headers = [],
        Stream $body = null,
        Form $form = null
    ) {
        $this->Method  = $method;
        $this->Uri     = new Uri($uri);
        $this->Headers = new Headers($headers);
        $this->Cookies = new Cookies($this->Headers);
        $this->Body    = $body;
        $this->Form    = $form;
        
        $this->setProtocol();
    }
}
