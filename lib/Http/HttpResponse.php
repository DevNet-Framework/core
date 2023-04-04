<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Async\Task;
use DevNet\System\IO\FileStream;
use DevNet\System\IO\Stream;

class HttpResponse extends HttpMessage
{
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

    private string $reasonPhrase;
    private int $statusCode;

    public function __construct(?Headers $headers = null, ?Stream $body = null)
    {
        $this->headers      = $headers ?? new Headers();
        $this->cookies      = new Cookies($this->headers);
        $this->body         = $body ?? new FileStream('php://temp', 'r+');
        $this->statusCode   = 200;
        $this->reasonPhrase = 'OK';
    }

    public function get_ReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function get_StatusCode(): string
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode, string $reasonPhrase = null)
    {
        if (!$reasonPhrase) {
            if (isset($this->messages[$statusCode])) {
                $reasonPhrase = $this->messages[$statusCode];
            }
        }

        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    public function getStatusLine(): string
    {
        return "{$this->protocol} {$this->statusCode} {$this->reasonPhrase}";
    }

    public function redirect(string $location, bool $permanent = false)
    {
        if ($permanent) {
            $this->setStatusCode(301);
        } else {
            $this->setStatusCode(302);
        }
        $this->headers->add('Location', $location);
    }

    public function writeAsync(string $content): Task
    {
        $body = $this->body;
        return Task::run(function() use($body, $content)
        {
            $body->write($content);
            return yield $body->flush();
        });
    }

    public function writeJsonAsync($value): Task
    {
        $headers = $this->headers;
        $body = $this->body;
        
        return Task::run(function() use($headers, $body, $value)
        {
            $headers->add("Content-Type", "application/json");
            $content = json_encode($value);
            $body->write($content);
            return yield $body->flush();
        });
    }
}
