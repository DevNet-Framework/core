<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

use DevNet\System\IO\Stream;

class Request extends HttpMessage
{
    private string $Method;
    private Uri $Uri;
    private Form $Form;
    private FileCollection $Files;
    private array $Attributes = [];

    public function __construct(
        ?string $method         = null,
        ?Uri $uri               = null,
        ?Headers $headers       = null,
        ?Cookies $cookies       = null,
        ?Stream $body           = null,
        ?Form $form             = null,
        ?FileCollection $files  = null
    ) {
        if (!$method) {
            $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        }

        if (!$uri) {
            $uri = new Uri();
        }

        if (!$headers) {
            $headers = new Headers(getallheaders());
        }

        if (!$cookies) {
            $cookies = new Cookies($headers);
        }

        if (!$body) {
            $body = new Stream('php://input', 'r');
        }

        if (!$form) {
            $form = new Form();
        }

        if (!$files) {
            $files = new FileCollection();
        }

        $this->Method  = $method;
        $this->Uri     = $uri;
        $this->Headers = $headers;
        $this->Cookies = $cookies;
        $this->Body    = $body;
        $this->Form    = $form;
        $this->Files   = $files;
        $this->setProtocol();
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function withAttribute(string $name, $value)
    {
        $this->Attributes[$name] = $value;
    }
}
