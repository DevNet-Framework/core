<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http\Client;

use DevNet\Core\Http\Headers;
use DevNet\System\Async\Tasks\Task;
use DevNet\Core\Http\HttpRequest;
use DevNet\Core\Http\Uri;

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

        $uri     = new Uri($url);
        $headers = new Headers(['host' => $uri->Host]);
        $request = new HttpRequest($method, $uri, $headers);
        
        $request->setProtocol($this->Options->HttpVersion);
        if ($requestContent) {
            $request->Headers->add('content-type', $requestContent->ContentType);
            $request->Headers->add('content-length', $requestContent->ContentLength);
            $request->Body->write($requestContent->Content);
        }

        return $this->sendAsync($request);
    }

    public function getStringAsync(string $url, ?HttpRequestContent $requestContent = null): Task
    {
        $task = $this->getAsync($url, $requestContent);
        return $task->then(function (Task $antecedent) {
            $response = $antecedent->Result;
            if ($response->Body->isReadable()) {
                return $response->Body->Read();
            }
        });
    }

    public function getAsync(string $url, ?HttpRequestContent $requestContent = null): Task
    {
        return $this->requestAsync('GET', $url, $requestContent);
    }

    public function postAsync(string $url, ?HttpRequestContent $requestContent): Task
    {
        return $this->requestAsync('POST', $url, $requestContent);
    }

    public function putAsync(string $url, ?HttpRequestContent $requestContent): Task
    {
        return $this->requestAsync('PUT', $url, $requestContent);
    }

    public function deleteAsync(string $url): Task
    {
        return $this->requestAsync('DELETE', $url);
    }
}
