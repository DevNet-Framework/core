<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\IO\FileStream;
use DevNet\System\IO\Stream;

class HttpRequest extends HttpMessage
{
    private string $method;
    private Uri $uri;
    private Form $form;

    public function __construct(
        string $method,
        string $uri,
        ?Headers $headers = null,
        ?Stream $body = null,
        ?Form $form = null
    ) {
        $this->method  = strtoupper($method);
        $this->uri     = new Uri($uri);
        $this->headers = $headers ?? new Headers();
        $this->cookies = new Cookies($this->Headers);
        $this->body    = $body ?? new FileStream('php://temp', 'r+');
        $this->form    = $form ?? new Form();
    }

    public function get_Method(): string
    {
        return $this->method;
    }

    public function get_Uri(): Uri
    {
        return $this->uri;
    }
    
    public function get_Form(): Form
    {
        return $this->form;
    }
}
