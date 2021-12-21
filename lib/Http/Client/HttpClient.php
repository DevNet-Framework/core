<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client;

use DevNet\System\Async\Tasks\Task;
use DevNet\System\IO\Stream;
use DevNet\Web\Http\HttpRequest;
use DevNet\Web\Http\Uri;

class HttpClient extends HttpClientHandler
{
    protected HttpClientOptions $Options;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __construct(?HttpClientOptions $options = null)
    {
        if (!$options) {
            $options = new HttpClientOptions();
        }

        $this->Options = $options;
    }

    public function requestAsync(string $method, string $url, ?HttpRequestContent $requestContent = null): Task
    {
        if (!empty($this->Options->BaseAddress)) {
            $url = $this->Options->BaseAddress . $url;
        }

        if ($requestContent) {
            $stream = new Stream('php://temp', 'r+');
            $stream->write($requestContent->Content);
            $request = new HttpRequest($method, new Uri($url), ['content-type' => $requestContent->ContentType], $stream);
        } else {
            $request = new HttpRequest($method, new Uri($url));
        }

        $request->setProtocol('HTTP/1.0');
        
        return $this->sendAsync($request);
    }

    public function getStringAsync(string $url, HttpRequestContent $requestContent = null): Task
    {
        $task = $this->getAsync($url, $requestContent);
        return $task->then(function (Task $precedent)
        {
            $response = $precedent->Result;
            if ($response->Body->isReadable()) {
                return $response->Body->Read();
            }
        });
    }

    public function getAsync(string $url, HttpRequestContent $requestContent = null): Task
    {
        return $this->requestAsync('GET', $url);
    }

    public function postAsync(string $url, HttpRequestContent $requestContent): Task
    {
        return $this->requestAsync('POST', $url);
    }

    public function putAsync(string $url, HttpRequestContent $requestContent): Task
    {
        return $this->requestAsync('PUT', $url);
    }

    public function deleteAsync(string $url): Task
    {
        return $this->requestAsync('DELETE', $url);
    }
}
