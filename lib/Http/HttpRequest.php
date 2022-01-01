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
    private string $Method;
    private Uri $Uri;
    private Form $Form;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(
        string $method,
        string $uri,
        ?Headers $headers = null,
        ?Stream $body = null,
        ?Form $form = null
    ) {
        $this->Method  = strtoupper($method);
        $this->Uri     = new Uri($uri);
        $this->Headers = $headers ?? new Headers();
        $this->Cookies = new Cookies($this->Headers);
        $this->Body    = $body ?? new FileStream('php://temp', 'r+');
        $this->Form    = $form ?? new Form();
    }
}
