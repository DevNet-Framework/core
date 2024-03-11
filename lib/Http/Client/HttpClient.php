<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client;

use DevNet\Web\Http\Message\Headers;
use DevNet\System\Async\Task;
use DevNet\System\PropertyTrait;
use DevNet\Web\Http\Message\HttpRequest;

class HttpClient extends HttpClientHandler
{
    use PropertyTrait;

    protected HttpClientOptions $options;

    public function __construct(?HttpClientOptions $options = null)
    {
        if (!$options) {
            $options = new HttpClientOptions();
        }

        $this->Options = $options;
    }

    public function get_Options(): HttpClientOptions
    {
        return $this->options;
    }

    public function requestAsync(string $method, string $url, ?HttpRequestContent $requestContent = null): Task
    {
        if (!empty($this->Options->BaseAddress)) {
            $url = $this->Options->BaseAddress . $url;
        }

        $host    = parse_url($url, PHP_URL_HOST);
        $headers = new Headers(['host' => $host]);
        $request = new HttpRequest($method, $url, $headers);

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
            if ($response->Body->IsReadable) {
                if ($response->Body->Length > 0) {
                    return $response->Body->read($response->Body->Length);
                }

                return '';
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
