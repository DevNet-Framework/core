<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Async\Tasks\Task;
use DevNet\System\IO\FileStream;
use DevNet\System\IO\Stream;

class HttpResponse extends HttpMessage
{
    protected string $ReasonPhrase;
    protected int $StatusCode;
    private array $messages = [
        // Informational 1xx
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        103 => "Early Hints",

        // Successful 2xx
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        208 => "Already Reported",
        210 => "Content Different",
        226 => "IM Used",

        // Redirection 3xx
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "(Unused)",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        310 => "Too many Redirects",

        // Client Error 4xx
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        414 => "Request-URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "I'm a teapot",
        421 => "Misdirected Request",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        451 => "Unavailable For Legal Reasons",

        // Server Error 5xx
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        510 => "Not Extended",
        511 => "Network Authentication Required",
    ];

    public function __get(string $name)
    {
        if ($name == 'ReasonPhrase') {
            return $this->ReasonPhrase;
        }

        if ($name == 'StatusCode') {
            return $this->StatusCode;
        }

        return parent::__get($name);
    }

    public function __construct(?Headers $headers = null, ?Stream $body = null)
    {
        $this->Headers      = $headers ?? new Headers();
        $this->Cookies      = new Cookies($this->Headers);
        $this->Body         = $body ?? new FileStream('php://temp', 'r+');
        $this->StatusCode   = 200;
        $this->ReasonPhrase = 'OK';
    }

    public function setStatusCode(int $statusCode, string $reasonPhrase = null)
    {
        if (!$reasonPhrase) {
            if (isset($this->messages[$statusCode])) {
                $reasonPhrase = $this->messages[$statusCode];
            }
        }

        $this->StatusCode = $statusCode;
        $this->ReasonPhrase = $reasonPhrase;
    }

    public function getStatusLine(): string
    {
        return "{$this->Protocol} {$this->StatusCode} {$this->ReasonPhrase}";
    }

    public function redirect(string $location, bool $permanent = false)
    {
        if ($permanent) {
            $this->setStatusCode(301);
        } else {
            $this->setStatusCode(302);
        }
        $this->Headers->add('Location', $location);
    }

    public function writeAsync(string $content): Task
    {
        $body = $this->Body;
        return Task::run(function() use($body, $content)
        {
            $body->write($content);
            return yield $body->flush();
        });
    }

    public function writeJsonAsync($value): Task
    {
        $content = json_encode($value);
        $this->Headers->add("Content-Type", "application/json");
        $result = $this->Body->write($content);
        return Task::fromResult($result);
    }
}
