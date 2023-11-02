<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\IO\FileAccess;
use DevNet\System\IO\FileMode;
use DevNet\System\IO\FileStream;
use DevNet\System\IO\Stream;

class HttpRequest extends HttpMessage
{
    private string $method;
    private string $scheme;
    private Host $host;
    private string $path;
    private Query $query;
    private Form $form;
    public array $RouteValues = [];

    public function __construct(
        string $method,
        string $url,
        ?Headers $headers = null,
        ?Stream $body = null,
        ?Form $form = null
    ) {
        $this->method  = strtoupper($method);
        $this->headers = $headers ?? new Headers();
        $this->cookies = new Cookies($this->Headers);
        $this->body    = $body ?? new FileStream('php://temp', FileMode::Open, FileAccess::ReadWrite);
        $this->form    = $form ?? new Form();

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!$scheme) {
            $this->scheme = 'http';
            $url = $this->scheme . '://' . $url;
        }

        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $this->host = new Host($host, $port);

        $this->path = parse_url($url, PHP_URL_PATH) ?? '/';

        $query = parse_url($url, PHP_URL_QUERY);
        $this->query = new Query($query);
    }

    public function get_Method(): string
    {
        return $this->method;
    }

    public function get_Scheme(): string
    {
        return $this->scheme;
    }

    public function get_Host(): Host
    {
        return $this->host;
    }

    public function get_Path(): string
    {
        return $this->path;
    }

    public function get_Query(): Query
    {
        return $this->query;
    }

    public function get_Form(): Form
    {
        return $this->form;
    }

    public function set_Path(string $value): void
    {
        $this->path = $value;
    }
}
